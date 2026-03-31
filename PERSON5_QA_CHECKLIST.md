# 📊 QA/Testing Engineer - Comprehensive Checklist

**Role**: Testing, quality assurance, documentation, bug tracking  
**Duration**: Tuần 2-4 (Depends on API implementation)  
**Dependencies**: Backend API (Person 1), Frontend (Person 2), AI Engine (Person 3)  

---

## 📅 TUẦN 2: Test Infrastructure & Unit Tests

### Task 2.1: Setup Pest/PHPUnit Framework
- [ ] Review current `tests/` structure:
  - [ ] `TestCase.php` - base class
  - [ ] `Pest.php` - configuration
  - [ ] `/Feature` - integration tests
  - [ ] `/Unit` - unit tests

- [ ] Configure `phpunit.xml`:
  ```xml
  <env name="DB_CONNECTION" value="testing"/>
  <env name="DB_DATABASE" value="sos_test"/>
  ```

- [ ] Create test database:
  ```bash
  php artisan migrate:fresh --env=testing
  ```

- [ ] Setup test utilities:
  - [ ] Database seeders for test data
  - [ ] Assert helper functions
  - [ ] Mock data factories

**Acceptance Criteria:**
- Tests can run: `php artisan test` ✓
- Test database isolated ✓

---

### Task 2.2: Unit Tests for Models
- [ ] Create `tests/Unit/Models/YeuCauCuuHoTest.php`:
  ```php
  public function test_yeu_cau_validates_required_fields()
  public function test_yeu_cau_gps_coordinates_valid_vn()
  public function test_user_max_3_pending_requests()
  ```

- [ ] Create `tests/Unit/Models/DoiCuuHoTest.php`:
  ```php
  public function test_doi_capacity_not_exceeded()
  public function test_doi_status_transitions_valid()
  ```

- [ ] Create `tests/Unit/Models/PhanCongCuuHoTest.php`:
  ```php
  public function test_phan_cong_status_flow()
  ```

- [ ] Create `tests/Unit/Services/AiScoringServiceTest.php` (Person 3 writes scoring logic):
  ```php
  public function test_score_critical_earthquake(): void {}
  public function test_score_low_urgency_drought(): void {}
  public function test_weights_applied_correctly(): void {}
  public function test_classification_thresholds(): void {}
  ```

**Target Coverage**: 100% for models + services

**Acceptance Criteria:**
- All model tests passing ✓
- 100% line coverage for models ✓

---

### Task 2.3: Unit Tests for Helpers/Utils
- [ ] `tests/Unit/Utils/DistanceCalculatorTest.php`:
  ```php
  public function test_haversine_known_coordinates(): void {
    // Hà Nội → HCM ≈ 1147 km
    $dist = DistanceCalculator::haversine(
      21.0285, 105.8542,
      10.7769, 106.7009
    );
    $this->assertBetween(1140, 1155, $dist);
  }
  ```

- [ ] `tests/Unit/Utils/ValidationTest.php`:
  ```php
  public function test_validate_vietnam_coordinates(): void {}
  public function test_validate_file_upload(): void {}
  ```

**Acceptance Criteria:**
- Utils tested ✓
- Edge cases covered ✓

---

### Task 2.4: Database Factory & Seeder for Testing
- [ ] Create/expand `database/factories/`:
  - [ ] `YeuCauCuuHoFactory.php` - generate test requests
  - [ ] `DoiCuuHoFactory.php` - generate test teams
  - [ ] `AdminFactory.php` - generate test admins
  - [ ] `NguoiDungFactory.php` - generate test users

- [ ] Example factory:
  ```php
  class YeuCauCuuHoFactory extends Factory {
    public function definition(): array {
      return [
        'id_nguoi_dung' => NguoiDung::factory(),
        'id_loai_su_co' => LoaiSuCo::inRandomOrder()->first()->id,
        'vi_tri_lat' => rand(810, 2340) / 100,  // 8.1 - 23.4
        'vi_tri_lng' => rand(10200, 11000) / 100,  // 102 - 110
        'so_nguoi_bi_anh_huong' => rand(1, 100),
        'muc_do_khan_cap' => 'MEDIUM',
        'mo_ta' => $this->faker->sentence(),
      ];
    }
  }
  ```

**Acceptance Criteria:**
- Factories generate valid data ✓
- Can create 1000+ test records quickly ✓

---

## 📅 TUẦN 3: Integration Tests & Feature Tests

### Task 3.1: API Endpoint Tests
- [ ] `tests/Feature/Api/YeuCauCuuHoTest.php`:
  ```php
  public function test_create_request_success() {
    $response = $this->postJson('/api/user/yeu-cau', [
      'id_loai_su_co' => 1,
      'vi_tri_lat' => 21.0285,
      'vi_tri_lng' => 105.8542,
      'so_nguoi_bi_anh_huong' => 5,
      'muc_do_khan_cap' => 'HIGH',
      'mo_ta' => 'Test request'
    ]);
    
    $response->assertStatus(201)
            ->assertJsonStructure(['id_yeu_cau', 'trang_thai']);
    $this->assertDatabaseHas('yeu_cau_cuu_ho', [
      'vi_tri_lat' => 21.0285
    ]);
  }
  
  public function test_create_request_invalid_coords() {
    $response = $this->postJson('/api/user/yeu-cau', [
      'vi_tri_lat' => 100,  // Out of Vietnam range
      'vi_tri_lng' => 200
    ]);
    $response->assertStatus(422);
  }
  
  public function test_rate_limit_exceeded() {
    // Create 4 requests, 4th should fail
  }
  ```

- [ ] `tests/Feature/Api/PhanCongCuuHoTest.php`:
  ```php
  public function test_assign_task_to_team() {}
  public function test_invalid_team_capacity() {}
  public function test_unsupported_disaster_type() {}
  ```

- [ ] `tests/Feature/Api/KetQuaCuuHoTest.php`:
  ```php
  public function test_submit_result_with_images() {}
  public function test_team_capacity_decremented() {}
  ```

- [ ] `tests/Feature/Api/AuthTest.php`:
  ```php
  public function test_user_login_success() {}
  public function test_admin_login_success() {}
  public function test_invalid_credentials() {}
  public function test_token_refresh() {}
  ```

**Target Coverage**: 80%+ for API routes

**Acceptance Criteria:**
- All CRUD operations tested ✓
- HTTP status codes correct ✓
- Database state verified after requests ✓

---

### Task 3.2: Complete Workflow Tests
- [ ] `tests/Feature/Workflows/FullRescueFlowTest.php`:
  ```php
  public function test_complete_rescue_workflow() {
    // Step 1: User sends request
    $request = YeuCauCuuHo::factory()->create([
      'trang_thai' => 'CHO_XU_LY'
    ]);
    
    // Step 2: AI scores (simulate or call real service)
    $this->artisan('app:test-ai-scoring', [
      'id' => $request->id_yeu_cau
    ]);
    $request->refresh();
    $this->assertNotNull($request->diem_uu_tien);
    
    // Step 3: Admin assigns team
    $team = DoiCuuHo::factory()->create();
    $assignment = PhanCongCuuHo::create([
      'id_yeu_cau' => $request->id_yeu_cau,
      'id_doi_cuu_ho' => $team->id_doi_cuu_ho,
      'trang_thai_nhiem_vu' => 'MOI'
    ]);
    
    // Step 4: Team completes task
    $assignment->update(['trang_thai_nhiem_vu' => 'HOAN_THANH']);
    
    // Step 5: Check state changes
    $request->refresh();
    $this->assertEquals('HOAN_THANH', $request->trang_thai);
    $team->refresh();
    $this->assertEquals('SAN_SANG', $team->trang_thai);
  }
  ```

- [ ] `tests/Feature/Workflows/CronJobsTest.php`:
  ```php
  public function test_recalculate_scores_cron() {
    // Create waiting requests
    $requests = YeuCauCuuHo::factory(5)->create();
    
    // Run cron
    $this->artisan('app:recalculate-scores');
    
    // Verify scores updated
    foreach ($requests as $req) {
      $req->refresh();
      $this->assertNotNull($req->diem_uu_tien);
    }
  }
  
  public function test_escalation_alerts_cron() {
    // Create HIGH request waiting 5 hours
    $request = YeuCauCuuHo::factory()->create([
      'created_at' => now()->subHours(5),
      'muc_do_khan_cap' => 'HIGH'
    ]);
    
    // Run escalation cron
    $this->artisan('app:check-escalation');
    
    // Verify escalated to CRITICAL
    $request->refresh();
    $this->assertEquals('CRITICAL', $request->muc_do_khan_cap);
  }
  
  public function test_heatmap_generation_cron() {
    // Create requests in various locations
    YeuCauCuuHo::factory(10)->create();
    
    // Run heatmap generation
    $this->artisan('app:generate-heatmap');
    
    // Verify heatmap data created
    $this->assertGreaterThan(0, DuLieuHeatmap::count());
  }
  ```

**Acceptance Criteria:**
- Full workflows pass ✓
- State transitions correct ✓
- Cron jobs execute ✓

---

### Task 3.3: Authorization & Permission Tests
- [ ] `tests/Feature/Authorization/AdminAuthorizationTest.php`:
  ```php
  public function test_admin_can_view_queue() {}
  public function test_non_admin_cannot_view_queue() {}
  public function test_role_permissions_enforced() {}
  public function test_denied_return_403() {}
  ```

- [ ] `tests/Feature/Authorization/UserRoleTest.php`:
  ```php
  public function test_user_cannot_assign_tasks() {}
  public function test_user_can_submit_request() {}
  ```

- [ ] `tests/Feature/Authorization/TeamRoleTest.php`:
  ```php
  public function test_team_can_update_location() {}
  public function test_team_cannot_see_other_team_data() {}
  ```

**Acceptance Criteria:**
- Permission tests pass ✓
- 403 returned correctly ✓

---

### Task 3.4: Error Handling Tests
- [ ] `tests/Feature/ErrorHandlingTest.php`:
  ```php
  public function test_invalid_json_request() {}
  public function test_missing_required_parameters() {}
  public function test_database_connection_error() {}
  public function test_file_upload_too_large() {}
  public function test_invalid_file_type() {}
  public function test_timeout_handling() {}
  ```

**Acceptance Criteria:**
- All error cases return proper HTTP status ✓
- Error messages helpful ✓

---

## 📅 TUẦN 4: Performance Testing, E2E, Documentation

### Task 4.1: Load Testing
- [ ] Install Artillery or ApacheBench:
  ```bash
  npm install -g artillery
  # or
  apt-get install apache2-utils
  ```

- [ ] Create load test scenarios:
  ```yaml
  # artillery.yml
  config:
    target: 'http://localhost:8000'
    phases:
      - duration: 60
        arrivalRate: 10  # 10 users/sec
      - duration: 120
        arrivalRate: 50
  
  scenarios:
    - name: "Create Request Flow"
      flow:
        - post:
            url: "/api/user/yeu-cau"
            json:
              id_loai_su_co: 1
              vi_tri_lat: 21.0285
              vi_tri_lng: 105.8542
        - think: 2
        - get:
            url: "/api/user/yeu-cau/{{ $captureVar.id }}"
  ```

- [ ] Run tests:
  ```bash
  artillery run artillery.yml
  ```

- [ ] Verify results:
  - [ ] P95 latency < 500ms
  - [ ] P99 latency < 1000ms
  - [ ] Error rate < 1%
  - [ ] Throughput > 100 req/sec

**Acceptance Criteria:**
- Load test passing ✓
- Performance baseline documented ✓

---

### Task 4.2: E2E Tests (Cypress/Selenium)
- [ ] Install Cypress:
  ```bash
  npm install --save-dev cypress
  npx cypress open
  ```

- [ ] Create E2E test: `cypress/e2e/user-flow.cy.js`
  ```javascript
  describe('User Request Flow', () => {
    it('should send request and track status', () => {
      cy.visit('http://localhost:3000');
      cy.login('user@test.com', 'password');
      cy.contains('Gửi Yêu Cầu').click();
      cy.get('[data-testid="disaster-type"]').select('dong-dat');
      cy.get('[data-testid="lat"]').type('21.0285');
      cy.get('[data-testid="lng"]').type('105.8542');
      cy.get('[data-testid="submit"]').click();
      cy.contains('Yêu cầu đã được gửi').should('be.visible');
    });
  });
  ```

- [ ] Create E2E test: `cypress/e2e/admin-flow.cy.js`
  ```javascript
  describe('Admin Dashboard Flow', () => {
    it('should view queue and assign task', () => {
      cy.adminLogin();
      cy.visit('http://localhost:3000/admin/queue');
      cy.contains('Hàng Đợi').should('be.visible');
      cy.get('table tbody tr').first().click();
      cy.contains('Phân Công Đội').click();
      // select team...
    });
  });
  ```

**Acceptance Criteria:**
- E2E tests pass ✓
- User workflows validated ✓

---

### Task 4.3: Security Testing
- [ ] OWASP Top 10 manual checks:
  - [ ] SQL Injection - input validation
  - [ ] XSS - output encoding
  - [ ] CSRF - token verification
  - [ ] Authentication bypass
  - [ ] Authorization bypass
  - [ ] Sensitive data exposure
  - [ ] XML External Entities (XXE)
  - [ ] Broken access control
  - [ ] Using components with vulnerabilities
  - [ ] Insufficient logging & monitoring

- [ ] Automated security scan:
  ```bash
  # PHP dependencies
  composer install --dev
  ./vendor/bin/phpstan analyse app/
  
  # JavaScript
  npm audit
  npm install npm-audit-fix
  npm-audit-fix
  ```

- [ ] Document findings:
  - [ ] Critical vulnerabilities found
  - [ ] Mitigation steps
  - [ ] Re-test after fixes

**Acceptance Criteria:**
- No critical vulnerabilities ✓
- Security report documented ✓

---

### Task 4.4: API Documentation Generation
- [ ] Generate Swagger/OpenAPI docs:
  ```bash
  php artisan l5-swagger:generate
  # Accessible at /api/docs
  ```

- [ ] Verify coverage:
  - [ ] All endpoints documented
  - [ ] Request/response schemas defined
  - [ ] Status codes documented
  - [ ] Error responses shown

- [ ] Generate user guides:
  - [ ] For người dùng (user guide)
  - [ ] For admin (admin guide)
  - [ ] For đội cứu hộ (team guide)
  - [ ] For developers (API guide)

**Acceptance Criteria:**
- Swagger UI accessible ✓
- All endpoints documented ✓
- User guides complete ✓

---

### Task 4.5: Bug Tracking & Final Testing
- [ ] Setup bug tracking (GitHub Issues or Trello):
  - [ ] Create template for bug reports
  - [ ] Categories: Critical, High, Medium, Low
  - [ ] Track resolution status

- [ ] Regression testing checklist:
  - [ ] All 24 use cases have corresponding test coverage
  - [ ] Spot check: manually test 10 workflows
  - [ ] Mobile testing on real devices
  - [ ] Cross-browser testing (Chrome, Firefox, Safari, Edge)
  - [ ] Different networks (WiFi, 4G, offline)

- [ ] Performance profiling:
  ```bash
  # Check slow queries
  php artisan tinker
  > DB::enableQueryLog();
  > // run operations
  > dd(DB::getQueryLog());
  
  # Memory usage
  > memory_get_peak_usage();
  ```

- [ ] Final sign-off checklist:
  - [ ] 21/21 tables working
  - [ ] 24/24 use cases implemented
  - [ ] 80%+ test coverage
  - [ ] < 200ms latency for list endpoints
  - [ ] < 500ms for complex operations
  - [ ] No security vulnerabilities
  - [ ] All error cases handled
  - [ ] Mobile responsive
  - [ ] Production ready

**Acceptance Criteria:**
- All bugs tracked ✓
- Regression tests pass ✓
- Production sign-off ready ✓

---

## ✅ Test Coverage Goals

| Type | Target | Week |
|------|--------|------|
| Unit Tests | 100% | 2 |
| Integration Tests | 80%+ | 3 |
| Feature Tests | 80%+ | 3 |
| E2E Tests | 15 workflows | 4 |
| Load Tests | Pass | 4 |
| Security Tests | Pass | 4 |
| **Overall Coverage** | **80%+** | **4** |

---

## 📋 Use Case Coverage Matrix

| UC # | Use Case | Test Type | Person | Status |
|------|----------|-----------|--------|--------|
| UC01 | Đăng ký | API + E2E | QA | ☐ |
| UC02 | Đăng nhập | API + E2E | QA | ☐ |
| UC03 | Gửi yêu cầu | API + E2E | QA | ☐ |
| UC04 | Xem trạng thái | API + E2E | QA | ☐ |
| UC05 | Đánh giá | API | QA | ☐ |
| UC06 | Dashboard | E2E | QA | ☐ |
| UC07 | Xem hàng đợi | API | QA | ☐ |
| UC08 | Phân công | API + E2E | QA | ☐ |
| UC09 | Bản đồ nhiệt | API | QA | ☐ |
| UC10 | GPS real-time | API | QA | ☐ |
| UC11 | Báo cáo | API | QA | ☐ |
| UC12 | Quản lý loại sự cố | API | QA | ☐ |
| UC13 | Quản lý đội | API | QA | ☐ |
| UC14 | Cấu hình trọng số | API | QA | ☐ |
| UC15 | Quản lý tài khoản | API | QA | ☐ |
| UC16 | Thông báo nhiệm vụ | Unit | QA | ☐ |
| UC17 | Cập nhật GPS | API | QA | ☐ |
| UC18 | Cập nhật tiến độ | API | QA | ☐ |
| UC19 | Nộp kết quả | API | QA | ☐ |
| UC20 | Yêu cầu tài nguyên | API | QA | ☐ |
| UC21 | Phân loại AI | Unit | QA | ☐ |
| UC22 | Tính điểm | Unit | QA | ☐ |
| UC23 | Cập nhật hàng đợi | Unit | QA | ☐ |
| UC24 | Heatmap | Unit | QA | ☐ |

---

## 🔄 Test Execution Order

**Week 2** (Parallel with Person 1 API dev):
```bash
# Run as Person 1 completes controllers
php artisan test tests/Unit/ --coverage
# Aim for 100% coverage on models/services
```

**Week 3** (Parallel with Person 1 API completion):
```bash
php artisan test tests/Feature/ --coverage
php artisan test tests/Feature/Workflows/
# Aim for 80%+ integration test coverage
```

**Week 4** (Testing complete, Person 2 Frontend done):
```bash
# Load tests
artillery run artillery.yml

# E2E tests
npx cypress run

# Security scan
./vendor/bin/phpstan analyse app/

# Full regression
php artisan test --coverage
```

---

## 🛠️ Testing Tools & Setup

**PHP Testing:**
- [ ] PHPUnit / Pest (already installed)
- [ ] Mockery (for mocking)
- [ ] Faker (for test data)
- [ ] Factory pattern

**JavaScript Testing:**
- [ ] Cypress (E2E)
- [ ] Jest (unit - frontend)
- [ ] Testing Library (component testing)

**Performance:**
- [ ] Artillery (load testing)
- [ ] Blackfire (profiling)
- [ ] Laravel Debugbar / Telescope

**Security:**
- [ ] PHPStan (static analysis)
- [ ] npm audit (dependency audit)
- [ ] OWASP ZAP (penetration testing)
- [ ] Snyk (vulnerability scanning)

---

## 📞 Collaboration with Other Person

**With Person 1 (Backend)**:
- Get list of new endpoints weekly
- Run tests immediately on new features
- Report bugs/edge cases

**With Person 2 (Frontend)**:
- Wait for E2E until frontend ready
- Share test data factories
- Get screenshots of UI bugs

**With Person 3 (AI)**:
- Test AI scoring with edge cases
- Verify cron job executions
- Monitor accuracy metrics

**With Person 4 (DevOps)**:
- Setup CI/CD pipeline to run tests on push
- Configure monitoring for test results
- Set up alerts for test failures

---

**Last Updated**: 2026-03-31  
**Status**: Not Started  
☐ Week 2 ☐ Week 3 ☐ Week 4 ☐ Complete
