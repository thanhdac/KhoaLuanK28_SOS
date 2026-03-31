# 🎯 Master Task Plan: 24 Use Cases Analysis

**Duration**: 4 tuần (01/04 - 30/04/2026)  
**Team**: 5 người  
**Goal**: Ship production-ready system với đầy đủ 24 use cases  

---

## 📊 Use Case Status Matrix

| UC # | Use Case | Backend | Frontend | AI/Cron | DevOps | QA | Status |
|------|----------|---------|----------|---------|--------|-----|--------|
| **UC01** | Đăng ký tài khoản người dùng | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC02** | Đăng nhập (người dùng/admin/team) | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC03** | Gửi yêu cầu cứu hộ | ⚪ | ⚪ | - | ⚪ | ⚪ | Not Started |
| **UC04** | Xem trạng thái yêu cầu | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC05** | Đánh giá sau cứu hộ | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC06** | Dashboard admin (KPI) | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC07** | Xem hàng đợi ưu tiên | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC08** | Phân công đội cứu hộ | ⚪ | ⚪ | ⚪ | ⚪ | ⚪ | Not Started |
| **UC09** | Bản đồ nhiệt khu vực | ⚪ | ⚪ | ⚪ | - | ⚪ | Not Started |
| **UC10** | Theo dõi vị trí đội real-time | ⚪ | ⚪ | - | ⚪ | ⚪ | Not Started |
| **UC11** | Báo cáo thống kê | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC12** | Quản lý loại sự cố | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC13** | Quản lý đội & tài nguyên | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC14** | Cấu hình trọng số AI | ⚪ | ⚪ | ⚪ | - | ⚪ | Not Started |
| **UC15** | Quản lý tài khoản & phân quyền | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC16** | Thông báo nhiệm vụ (FCM) | ⚪ | ⚪ | - | ⚪ | ⚪ | Not Started |
| **UC17** | Cập nhật GPS real-time | ⚪ | ⚪ | - | ⚪ | ⚪ | Not Started |
| **UC18** | Cập nhật tiến độ xử lý | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC19** | Nộp kết quả + ảnh | ⚪ | ⚪ | - | ⚪ | ⚪ | Not Started |
| **UC20** | Yêu cầu tài nguyên bổ sung | ⚪ | ⚪ | - | - | ⚪ | Not Started |
| **UC21** | Tự động phân loại khẩn cấp (AI) | ⚪ | - | ⚫ | - | ⚪ | **Core** |
| **UC22** | Tính điểm ưu tiên (AI scoring) | ⚪ | - | ⚫ | - | ⚪ | **Core** |
| **UC23** | Cập nhật hàng đợi theo thứ tự | ⚪ | ⚪ | ⚫ | ⚪ | ⚪ | **Core** |
| **UC24** | Cập nhật cache heatmap (Cron) | ⚪ | ⚪ | ⚫ | ⚪ | ⚪ | **Core** |

**Legend:**
- ⚪ = Not Started
- 🟡 = In Progress
- ✅ = Done
- ⚫ = Critical Path (blocks other tasks)

---

## 🔴 Critical Path Items (Dependencies)

```
┌─────────────────────────────────────────────────────┐
│                   CRITICAL PATH                     │
└─────────────────────────────────────────────────────┘

Week 1:
  ✓ Database (DONE)
  ✓ Models (DONE)
  ✓ Seeders (DONE)
       │
       ▼
  → UC01/UC02: Authentication API
       │
       ▼
  → UC03: Create Request Handler
       │
       ▼
  → UC21/UC22: AI Scoring Engine
       │
       ▼
  → UC23: Queue Management

Week 2-3:
  → UC08: Team Assignment (depends on UC21/UC22)
       │
       ▼
  → UC18/UC19: Task Completion (depends on UC08)

Week 3-4:
  → UC24: Heatmap Calculation (depends on UC03/UC21)
       │
       ▼
  → UC09: Map Visualization (depends on UC24)

Side track (no dependencies):
  ✓ UC04, UC05, UC06, UC07, UC11, UC12, UC13, UC14, UC15
    (can start anytime after auth)
  ✓ UC10, UC16, UC17, UC20 (depends on auth)
```

---

## 📅 Week-by-Week Timeline

### **TUẦN 1: Foundation & Core API**

**Goal**: Auth + Create request + AI Engine ready

| Task | Person | Status |
|------|--------|--------|
| UC01-UC02: Authentication | 1 | ☐ |
| Auth tests | 5 | ☐ |
| Login/Register UI | 2 | ☐ |
| UC03: Create Request API | 1 | ☐ |
| File upload handler | 4 | ☐ |
| Create request UI | 2 | ☐ |
| UC21-UC22: AI Scoring | 3 | ☐ |
| AI tests | 5 | ☐ |

**Deliverables**:
- ✅ User can register + login
- ✅ User can send request with GPS + images
- ✅ AI scores request correctly
- ✅ 15+ tests passing

---

### **TUẦN 2: Queue & Phân Công**

**Goal**: Queue system + automatic scheduling + team assignment

| Task | Person | Status |
|------|--------|--------|
| UC07: Queue Management API | 1 | ☐ |
| Queue UI | 2 | ☐ |
| UC23: Recalc scores cron | 3 | ☐ |
| UC08: Team assignment API | 1 | ☐ |
| Assignment UI + gợi ý | 2 | ☐ |
| Distance calculator | 3 | ☐ |
| UC16: FCM notifications | 4 | ☐ |
| Integration tests | 5 | ☐ |

**Deliverables**:
- ✅ Queue sorted by priority
- ✅ Average wait time tracked
- ✅ Team suggestions working
- ✅ Notifications sent
- ✅ 30+ tests passing

---

### **TUẦN 3: Execution & Heatmap**

**Goal**: Team workflow + tracking + heatmap

| Task | Person | Status |
|------|--------|--------|
| UC17: GPS tracking API | 1 | ☐ |
| Tracking UI with map | 2 | ☐ |
| UC18-UC19: Complete task API | 1 | ☐ |
| Result submission UI | 2 | ☐ |
| UC05: Rating system | 1 | ☐ |
| Rating UI | 2 | ☐ |
| UC24: Heatmap cron job | 3 | ☐ |
| UC09: Heatmap visualization | 2 | ☐ |
| Performance tests | 5 | ☐ |

**Deliverables**:
- ✅ Team can update location every 60s
- ✅ Complete task workflow working
- ✅ Results submitted with images
- ✅ Heatmap generating every 15 min
- ✅ 50+ tests, 80%+ coverage

---

### **TUẦN 4: Polish & Deployment**

**Goal**: Dashboard + analytics + production ready

| Task | Person | Status |
|------|--------|--------|
| UC06: Admin dashboard + charts | 2 | ☐ |
| UC11: Reports & analytics | 1,2 | ☐ |
| UC12-15: Admin management pages | 2 | ☐ |
| Authorization middleware | 1 | ☐ |
| Dark mode + accessibility | 2 | ☐ |
| E2E tests (Cypress) | 5 | ☐ |
| Load testing (1000 req/s) | 5 | ☐ |
| Security audit | 5 | ☐ |
| Docker build & CI/CD | 4 | ☐ |
| Documentation complete | 5 | ☐ |

**Deliverables**:
- ✅ Full admin dashboard
- ✅ All 24 use cases working
- ✅ 80%+ test coverage
- ✅ < 200ms latency for lists
- ✅ < 500ms for complex ops
- ✅ Production deployment ready
- ✅ Documentation complete

---

## ❌ Current Gaps vs Roadmap

### **Backend (Person 1)**

| Feature | Status | Gap |
|---------|--------|-----|
| Database schema | ✅ 21/21 tables | 0% |
| Models relationships | ✅ Done | 0% |
| Authentication | ❌ 0% | 100% |
| Create request handler | ❌ 0% | 100% |
| Request list + filter | ❌ 0% | 100% |
| Queue management | ❌ 0% | 100% |
| Team assignment logic | ❌ 0% | 100% |
| Task completion workflow | ❌ 0% | 100% |
| Result submission | ❌ 0% | 100% |
| GPS tracking API | ❌ 0% | 100% |
| Rating system | ❌ 0% | 100% |
| Admin CRUD (loại sự cố, đội, etc) | ❌ 0% | 100% |
| Authorization middleware | ❌ 0% | 100% |
| API documentation | ❌ 0% | 100% |
| **Total Backend** | **~5%** | **95%** |

**Priority**:
1. Authentication (UC01/UC02) - Week 1 - CRITICAL
2. Create request (UC03) - Week 1 - CRITICAL
3. Queue + assignment (UC07/UC08) - Week 2 - CRITICAL
4. Rate limiting + validation - Week 1-2
5. Admin endpoints - Week 3-4

---

### **Frontend (Person 2)**

| Feature | Status | Gap |
|---------|--------|-----|
| Vue 3 + Router setup | 🟡 Some | 20% |
| Tailwind CSS | ⚪ None | 100% |
| Auth store (Pinia) | ❌ 0% | 100% |
| Login/Register forms | ❌ 0% | 100% |
| User dashboard | ❌ 0% | 100% |
| Create request form + map | ❌ 0% | 100% |
| Admin dashboard + KPI cards | ❌ 0% | 100% |
| Queue table + assignment modal | ❌ 0% | 100% |
| Heatmap Leaflet integration | ❌ 0% | 100% |
| Team tracking screen | ❌ 0% | 100% |
| Charts (Chart.js) | ❌ 0% | 100% |
| Responsive design (mobile) | ❌ 0% | 100% |
| **Total Frontend** | **~5%** | **95%** |

**Priority**:
1. Auth UI (login/register) - Week 1
2. User request form - Week 1
3. Admin queue + assignment - Week 2
4. Admin dashboard - Week 4
5. Heatmap + tracking - Week 3

---

### **AI/ML (Person 3)**

| Feature | Status | Gap |
|---------|--------|-----|
| Scoring service | ❌ 0% | 100% |
| Feature normalization | ❌ 0% | 100% |
| Classification (CRITICAL/HIGH/etc) | ❌ 0% | 100% |
| PhanLoaiAis async job | ❌ 0% | 100% |
| Recalculate scores cron (30 min) | ❌ 0% | 100% |
| Escalation alerts cron (15 min) | ❌ 0% | 100% |
| Haversine distance calc | ❌ 0% | 100% |
| Heatmap aggregation cron (15 min) | ❌ 0% | 100% |
| Admin weight adjustment | ❌ 0% | 100% |
| Model versioning & audit trail | ❌ 0% | 100% |
| **Total AI** | **~0%** | **100%** |

**Priority**:
1. Scoring algorithm - Week 1 - CRITICAL (blocks queue)
2. Cron infrastructure - Week 1-2
3. Performance optimization - Week 3
4. Admin UI for weights - Week 4

---

### **DevOps (Person 4)**

| Feature | Status | Gap |
|---------|--------|-----|
| Local file storage | 🟡 Partial | 50% |
| Firebase FCM setup | ❌ 0% | 100% |
| Queue system (Redis/DB) | ❌ 0% | 100% |
| Redis caching | ❌ 0% | 100% |
| Rate limiting | ❌ 0% | 100% |
| Docker setup | ❌ 0% | 100% |
| CI/CD (GitHub Actions) | ❌ 0% | 100% |
| Monitoring (Sentry/Telescope) | ❌ 0% | 100% |
| Backups & disaster recovery | ❌ 0% | 100% |
| SSL certificate (production) | ❌ 0% | 100% |
| **Total DevOps** | **~5%** | **95%** |

**Priority**:
1. File upload + compression - Week 1
2. FCM notifications - Week 2
3. Queue system - Week 1-2
4. Docker + CI/CD - Week 3-4
5. Production deploy - Week 4

---

### **QA/Testing (Person 5)**

| Feature | Status | Gap |
|---------|--------|-----|
| Test database setup | ⚪ None | 100% |
| Factories for test data | ❌ 0% | 100% |
| Unit tests (80% coverage) | ❌ 0% | 100% |
| Integration tests for all APIs | ❌ 0% | 100% |
| E2E tests (Cypress) | ❌ 0% | 100% |
| Load testing (Artillery) | ❌ 0% | 100% |
| Security testing (OWASP) | ❌ 0% | 100% |
| Performance profiling | ❌ 0% | 100% |
| Bug tracking & regression | ❌ 0% | 100% |
| Documentation (user guides) | ❌ 0% | 100% |
| **Total QA** | **~0%** | **100%** |

**Priority**:
1. Test infrastructure + factories - Week 2
2. Unit tests for models/services - Week 2
3. Integration tests - Week 3
4. E2E + load tests - Week 4
5. Final regression - Week 4

---

## 🚨 Key Bottlenecks & Risks

### **Critical Dependencies**

1. **AI Scoring Engine (UC21/UC22)** - blocks UC23, UC08
   - If delayed: queue system can't work, can't assign teams
   - Mitigation: Start Week 1, 3 days to complete
   - Backup: Use mock scoring until real ready

2. **Authentication (UC01/UC02)** - blocks all protected endpoints
   - If delayed: frontend can't login
   - Mitigation: Start Week 1 Day 1, 2 days to complete
   - Backup: Mock auth for frontend development

3. **File Upload (UC03)** - needed for image storage
   - If delayed: requests without images only
   - Mitigation: Start Week 1, integrate DevOps + Backend
   - Backup: Use temp directory first

4. **Frontend Framework** - needed for all UI
   - If delayed: can't test UI
   - Mitigation: Already setup, just styling needed
   - Backup: Use CDN for quick prototyping

### **Integration Points (High Risk)**

- Backend ↔ Frontend: API contracts need agreement (Swagger docs)
- Backend ↔ AI: Scoring results flow to API
- Backend ↔ DevOps: File storage paths, notifications, queue
- Frontend ↔ DevOps: FCM tokens, WebSocket endpoints
- All components ↔ QA: Tests must be mock-friendly

### **Performance Risks**

- Scoring 1000 requests simultaneously (need batch optimization)
- GPS updates every 60s × 100 teams = high throughput
- Heatmap grid aggregation with 10000+ requests/day
- Mitigation: Start performance testing Week 2, optimize Week 3

---

## 📋 Success Criteria

### **Week 1 Completion**
- ✅ Authentication API working (UC01/UC02)
- ✅ User can send request with GPS + images (UC03)
- ✅ AI scores requests correctly (UC21/UC22)
- ✅ 30+ tests passing, 100% coverage on core logic
- ✅ 0 security issues found

### **Week 2 Completion**
- ✅ Queue system working + sorting (UC07/UC23)
- ✅ Team assignment + scoring system (UC08)
- ✅ Notifications sending (UC16)
- ✅ No critical bugs in Week 1 features
- ✅ 60+ tests passing, 80%+ coverage

### **Week 3 Completion**
- ✅ Complete task workflow working (UC18/UC19)
- ✅ GPS tracking real-time (UC17)
- ✅ Heatmap generating + visualizing (UC09/UC24)
- ✅ All core use cases (1-24) have basic implementation
- ✅ 100+ tests passing

### **Week 4 Completion**
- ✅ Admin dashboard fully functional (UC06)
- ✅ All use cases fully polished
- ✅ 150+ tests, 80%+ coverage
- ✅ E2E tests passing
- ✅ Load tests passing (< 200ms for lists, < 500ms complex)
- ✅ No security vulnerabilities
- ✅ Production deployment ready
- ✅ Full documentation

---

## 📞 Coordination Points

### **Daily Standup** (10 min)
- What I finished
- What I'm doing today
- Blockers?

### **Twice Weekly Sync** (30 min)
- **Monday 10am**: Plan for week, assign priorities
- **Friday 4pm**: Review progress, identify risks

### **Integration Points** (twice per week)
- **Mon/Wed 11am**: Backend + Frontend API contract review
- **Tue/Thu 2pm**: Backend + DevOps infrastructure check

### **Testing Gates** (before releasing feature)
- Person 1 (Backend): Unit tests pass, 100% on new code
- Person 2 (Frontend): E2E test passes, responsive on mobile
- Person 3 (AI): Scoring accuracy > 95%
- Person 4 (DevOps): No infrastructure issues
- Person 5 (QA): No critical/high bugs

---

## 🎯 Definition of Done (Per Feature)

A feature is only "Done" when:

1. **Code Complete**
   - [ ] Implemented per business logic
   - [ ] All edge cases handled
   - [ ] No console errors/warnings
   - [ ] No TODO comments left

2. **Tested**
   - [ ] Unit tests: 100% coverage
   - [ ] Integration tests: passes
   - [ ] Manual tests: 3 scenarios
   - [ ] No critical/high bugs

3. **Documented**
   - [ ] Code comments (complex logic)
   - [ ] API docs (if backend)
   - [ ] User guide (if UI)

4. **Reviewed**
   - [ ] Code review: 1 other person
   - [ ] QA approval: tested
   - [ ] Security review: no issues

5. **Integrated**
   - [ ] Works with other components
   - [ ] No regression in existing features
   - [ ] Performance acceptable (< 500ms)

---

## 📊 Estimation Summary

| Component | Effort (days) | Start | End | Person |
|-----------|---------------|-------|-----|--------|
| Auth API | 2 | W1 D1 | W1 D2 | P1 |
| Create request API | 3 | W1 D1 | W1 D3 | P1 |
| AI scoring | 3 | W1 D1 | W1 D3 | P3 |
| Queue management | 2 | W2 D1 | W2 D2 | P1 |
| Team assignment | 2 | W2 D1 | W2 D2 | P1 |
| GPS tracking | 1 | W3 D1 | W3 D1 | P1 |
| Task completion | 2 | W3 D1 | W3 D2 | P1 |
| Heatmap backend | 2 | W3 D1 | W3 D2 | P3 |
| **Backend Subtotal** | **~24 days** | | | |
| Auth UI | 2 | W1 D1 | W1 D2 | P2 |
| Request form + map | 3 | W1 D1 | W1 D3 | P2 |
| Queue UI | 2 | W2 D1 | W2 D2 | P2 |
| Assignment modal | 2 | W2 D1 | W2 D2 | P2 |
| Team tracking screen | 2 | W3 D1 | W3 D2 | P2 |
| Heatmap visualization | 2 | W3 D1 | W3 D2 | P2 |
| Admin dashboard | 4 | W4 D1 | W4 D2 | P2 |
| Polish + responsive | 2 | W4 D3 | W4 D4 | P2 |
| **Frontend Subtotal** | **~21 days** | | | |
| Scoring algorithm | 3 | W1 D1 | W1 D3 | P3 |
| Cron infrastructure | 2 | W2 D1 | W2 D2 | P3 |
| Heatmap generation | 2 | W3 D1 | W3 D2 | P3 |
| Optimization + testing | 2 | W3 D3 | W3 D4 | P3 |
| **AI Subtotal** | **~9 days** | | | |
| Storage setup | 1 | W1 D1 | W1 D1 | P4 |
| FCM setup | 2 | W2 D1 | W2 D2 | P4 |
| Queue/Redis | 2 | W1 D2 | W1 D3 | P4 |
| Notifications | 1 | W2 D3 | W2 D3 | P4 |
| Docker + CI/CD | 3 | W3 D1 | W3 D3 | P4 |
| Monitoring | 1 | W4 D1 | W4 D1 | P4 |
| **DevOps Subtotal** | **~10 days** | | | |
| Test infrastructure | 2 | W2 D1 | W2 D2 | P5 |
| Unit tests | 3 | W2 D3 | W3 D1 | P5 |
| Integration tests | 3 | W3 D2 | W3 D4 | P5 |
| E2E + Load tests | 2 | W4 D1 | W4 D2 | P5 |
| Docs + bug tracking | 1 | W4 D3 | W4 D3 | P5 |
| **QA Subtotal** | **~11 days** | | | |
| **TOTAL** | **~75 person-days** | | | |
| **Calendar** | **20 days** | W1 | W4 | 5 people |

---

## ✅ Ready to Execute

**All 5 checklists created:**
- ✅ PERSON1_BACKEND_CHECKLIST.md - 24 days work, detailed tasks/tests
- ✅ PERSON2_FRONTEND_CHECKLIST.md - 21 days work, UI components
- ✅ PERSON3_AI_CHECKLIST.md - 9 days work, core algorithms
- ✅ PERSON4_DEVOPS_CHECKLIST.md - 10 days work, infrastructure
- ✅ PERSON5_QA_CHECKLIST.md - 11 days work, comprehensive testing

**Next Steps:**
1. Print this master plan + individual checklists
2. Assign each person their checklist
3. Start Week 1 Monday morning
4. Daily standup 10am
5. Twice-weekly sync points

---

**Last Update**: 2026-03-31  
**Status**: Ready to execute  
**Next Milestone**: Week 1 Auth API complete (2026-04-05)
