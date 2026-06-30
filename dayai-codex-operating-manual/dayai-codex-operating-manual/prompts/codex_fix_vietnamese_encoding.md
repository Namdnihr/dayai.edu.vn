# Codex Prompt — Fix Vietnamese Encoding

```text
Read AGENTS.md first.

Task:
Find and fix broken Vietnamese encoding in DAYAI UI and seed/demo data.

Search for suspicious strings:
- Pháº
- HÃ
- Ä
- á»
- Láº
- Nguyá
- trá»

Check:
- frontend constants
- seed data
- fixtures
- database demo data scripts
- API response mapping
- rendering components

Rules:
- Do not change valid Vietnamese strings.
- Keep files UTF-8.
- User-facing text must be natural Vietnamese.
- Report changed files and examples fixed.
- Run available checks.
```
