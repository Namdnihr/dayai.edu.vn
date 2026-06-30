# Security Gate

Before shipping private/admin/portal changes:

- [ ] Admin routes require authentication
- [ ] Role permissions enforced server-side
- [ ] Portal routes are noindex
- [ ] OTP is rate-limited
- [ ] OTP/tokens are not logged
- [ ] Secrets are not exposed in client bundle
- [ ] Error messages do not leak internals
- [ ] Finance/private student data not exposed publicly
- [ ] Demo credentials isolated from production
- [ ] Audit log exists for sensitive auth events if supported
