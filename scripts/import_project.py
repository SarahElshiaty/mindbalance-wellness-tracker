#!/usr/bin/env python3
"""Import the collaborative MindBalance code without user records."""
from pathlib import Path
import re, shutil, subprocess, sys

src=Path(sys.argv[1]).resolve()
dst=Path(sys.argv[2]).resolve()
if not (src/"database/mindbalance_db.sql").is_file():
    raise SystemExit("Original database file missing")
if (dst/"index.php").exists():
    raise SystemExit("Project was already imported; refusing overwrite")

for folder in ("assets","screenshots"):
    shutil.copytree(src/folder,dst/folder,dirs_exist_ok=True)
shutil.copy2(src/"styles.css",dst/"styles.css")
files=[p for d in ("pages","actions","admin","core") for p in (src/d).glob("*.php")]
if len(set(p.name for p in files)) != len(files):
    raise SystemExit("Conflicting PHP filenames")
images=[p.name for p in (dst/"assets/images").iterdir() if p.is_file()]
for p in files:
    content=p.read_text(encoding="utf-8-sig")
    for name in sorted(images,key=len,reverse=True):
        content=re.sub(r"(?<![a-zA-Z0-9_/])"+re.escape(name),"assets/images/"+name,content)
    (dst/p.name).write_text(content,encoding="utf-8")

schema=(src/"database/mindbalance_db.sql").read_text(encoding="utf-8-sig")
schema=re.sub(r"(?ms)^\s*INSERT INTO\s+.*?;\s*(?=\r?\n|$)","\n",schema)
if re.search(r"(?im)^\s*INSERT INTO\b",schema) or schema.count("CREATE TABLE")<5:
    raise SystemExit("Unsafe or incomplete schema; aborting")
if any(s in schema for s in ("alice_sm@gmail.com","$2y$","mashael12@gmail.com")):
    raise SystemExit("Personal data remains; aborting")
(dst/"database").mkdir(exist_ok=True)
(dst/"database/mindbalance_schema.sql").write_text(schema,encoding="utf-8")

# Replace the shared database include with local-only configuration.
(dst/"db_connect.php").write_text("""<?php
$host = getenv('MINDBALANCE_DB_HOST') ?: 'localhost';
$db = getenv('MINDBALANCE_DB_NAME') ?: 'mindbalance_db';
$user = getenv('MINDBALANCE_DB_USER') ?: 'root';
$pass = getenv('MINDBALANCE_DB_PASSWORD');
$pass = $pass === false ? '' : $pass;
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false];
try {
    $pdo = new PDO($dsn,$user,$pass,$options);
} catch (PDOException $e) {
    error_log('MindBalance database connection failed: '.$e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check local settings.');
}
""",encoding="utf-8")

(dst/"get_log.php").write_text("""<?php
session_start();
require __DIR__ . '/db_connect.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }
$id=filter_input(INPUT_GET,'log_id',FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit; }
$stmt=$pdo->prepare('SELECT log_id,mood,note,log_date FROM mood_logs WHERE log_id=? AND user_id=?');
$stmt->execute([$id,$_SESSION['user_id']]);
$log=$stmt->fetch();
if (!$log) { http_response_code(404); exit; }
echo json_encode($log);
""",encoding="utf-8")
(dst/"update_log.php").write_text("""<?php
session_start();
require __DIR__ . '/db_connect.php';
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }
if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit; }
$id=filter_input(INPUT_POST,'log_id',FILTER_VALIDATE_INT);
$mood=trim($_POST['mood']??'');
$note=trim($_POST['note']??'');
$date=trim($_POST['log_date']??'');
if (!$id || $mood==='' || $date==='') { http_response_code(400); exit; }
$stmt=$pdo->prepare('UPDATE mood_logs SET mood=?,note=?,log_date=? WHERE log_id=? AND user_id=?');
$stmt->execute([$mood,$note,$date,$id,$_SESSION['user_id']]);
echo 'success';
""",encoding="utf-8")
history=dst/"history.php"
h=history.read_text(encoding="utf-8")
if "DELETE FROM mood_logs WHERE log_id = ?" not in h or "$stmt->execute([$log_id]);" not in h:
    raise SystemExit("Unexpected history handler")
h=h.replace("if (isset($_POST['delete_log'])) {","if (isset($_POST['delete_log']) && isset($_SESSION['user_id'])) {")
h=h.replace("DELETE FROM mood_logs WHERE log_id = ?","DELETE FROM mood_logs WHERE log_id = ? AND user_id = ?")
h=h.replace("$stmt->execute([$log_id]);","$stmt->execute([$log_id, $_SESSION['user_id']]);")
history.write_text(h,encoding="utf-8")
tracker=dst/"tracker.php"
t=tracker.read_text(encoding="utf-8")
needle="if ($_SERVER['REQUEST_METHOD'] === 'POST') {"
if needle not in t: raise SystemExit("Unexpected tracker handler")
t=t.replace(needle,needle+"\n    if (!isset($_SESSION['user_id'])) { header('Location: signin.php'); exit; }",1)
tracker.write_text(t,encoding="utf-8")
(dst/".gitignore").write_text(".env\n.env.*\n!.env.example\nvendor/\nnode_modules/\n.vscode/\n.idea/\n*.log\n",encoding="utf-8")
(dst/".gitattributes").write_text("* text=auto\n*.png binary\n*.jpg binary\n*.jpeg binary\n",encoding="utf-8")

for name in ("index.php","signin.php","register.php","tracker.php","history.php","db_connect.php","screenshots/home.png","database/mindbalance_schema.sql"):
    if not (dst/name).is_file(): raise SystemExit("Missing "+name)
php=shutil.which("php")
if php:
    n=0
    for f in dst.glob("*.php"):
        subprocess.run([php,"-l",str(f)],check=True,stdout=subprocess.DEVNULL)
        n+=1
    print("PHP lint passed:",n,"files")
else:
    print("PHP CLI absent: lint not performed")
print("Sanitized project prepared; no existing user records copied")
