#!/usr/bin/env python3
"""HTTP smoke tests against a disposable local MindBalance instance."""
import http.cookiejar
import urllib.error
import urllib.parse
import urllib.request

BASE = "http://127.0.0.1:8080"
def client():
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
def request(opener, path, data=None):
    payload = urllib.parse.urlencode(data).encode() if data is not None else None
    try:
        r = opener.open(BASE + "/" + path, data=payload, timeout=10)
        return r.status, r.geturl(), r.read().decode("utf-8", errors="replace")
    except urllib.error.HTTPError as e:
        return e.code, e.geturl(), e.read().decode("utf-8", errors="replace")

a = client()
b = client()
for path in ("index.php", "signin.php", "register.php", "tips.php", "tracker.php"):
    status, url, content = request(a, path)
    assert status == 200, (path, status, content[:250])
    assert "Fatal error" not in content and "Warning:" not in content, (path, content[:300])
    print("OK GET", path)
for email, opener in (("usera@example.test", a), ("userb@example.test", b)):
    status, url, content = request(opener, "register.php", {
        "name": "Test User", "email": email, "password": "TestPass123!",
        "confirm": "TestPass123!"})
    assert status == 200 and "register.php" in url, ("registration", status, url, content[:200])
    status, url, content = request(opener, "submit_login.php", {
        "email": email, "password": "TestPass123!"})
    assert status == 200 and "index.php" in url, ("login", status, url, content[:300])
    print("OK registration and login", email)
status, url, content = request(a, "tracker.php", {
    "mood": "Happy", "note": "Automated test entry", "log_date": "2026-11-07"})
assert status == 200 and "Success!" in content, ("mood save", status, url, content[:300])
status, url, content = request(a, "history.php")
assert status == 200 and "Automated test entry" in content, ("history", status, url)
status, url, content = request(b, "history.php")
assert "Automated test entry" not in content, "Another user can view mood log"
print("OK mood save and per-user history")
status, url, content = request(b, "admin.php")
assert url.endswith("/index.php") or status in (401, 403), ("admin access", url, status)
print("OK non-admin cannot view admin page")
print("SMOKE TEST PASSED")
