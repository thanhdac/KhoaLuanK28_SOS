# 👨‍💻 Person 1: Backend API Developer

**Role**: Hoàn thiện API backend, business logic, validation  
**Duration**: Tuần 1-4 (Ưu tiên: Critical Path)  
**Dependencies**: Database schema (✅ Done), Seeders (✅ Done)  

---

## 📅 TUẦN 1: Yêu Cầu Cứu Hộ & AI Scoring (UC03, UC21, UC22)

### Task 1.1: YeuCauCuuHoController - Gửi Yêu Cầu
- [ ] Implement `store()` method với validation:
  - [ ] `vi_tri_lat`, `vi_tri_lng` bắt buộc (validate tọa độ VN)
  - [ ] `id_loai_su_co` phải tồn tại trong `loai_su_co`
  - [ ] `so_nguoi_bi_anh_huong` <= 9999
  - [ ] Rate limit: max 3 yêu cầu CHO_XU_LY per user
- [ ] File upload handler (hình ảnh):
  - [ ] Accept JPG, PNG (max 5MB mỗi file)
  - [ ] Store trong `storage/app/uploads/requests/`
  - [ ] Lưu đường dẫn vào data yêu cầu
- [ ] INSERT `yeu_cau_cuu_ho` với `trang_thai = 'CHO_XU_LY'`
- [ ] Dispatch async job `ProcessAiScoringJob`
- [ ] Response return id + timing

**Acceptance Criteria:**
- Gửi yêu cầu thành công → Status 201
- Hình ảnh được lưu → Path trả về trong response
- Rate limit trigger → Status 429

**Tests to Write:**
```
- test_create_yeu_cau_success()
- test_create_with_invalid_coords()
- test_rate_limit_exceeded()
- test_file_upload_success()
```

---

### Task 1.2: PhanLoaiAisController - AI Scoring Engine
- [ ] Implement `score()` method (main AI logic):
  - [ ] Input: `id_yeu_cau`
  - [ ] Query `yeu_cau_cuu_ho` + `loai_su_co` + `trong_so_phan_loai`
  - [ ] Calculate 5 features:
    - [ ] `score_loai` (loại sự cố)
    - [ ] `score_so_nguoi` (số người ảnh hưởng)
    - [ ] `score_tu_bao_cao` (tự báo cáo)
    - [ ] `score_thoi_gian` (thời gian chờ)
    - [ ] `score_khu_vuc` (khu vực nguy hiểm - mặc định 0 tuần này)
  - [ ] Apply weights W1-W5 từ `trong_so_phan_loai`
  - [ ] Calculate `diem_uu_tien = sum(W * score)`
  - [ ] Classify level: CRITICAL/HIGH/MEDIUM/LOW
- [ ] INSERT `phan_loai_ais` record (lưu chi tiết scoring)
- [ ] UPDATE `yeu_cau_cuu_ho` set `diem_uu_tien`, `muc_do_khan_cap`
- [ ] UPDATE `hang_doi_xu_ly` / INSERT (xem task 1.3)

**Feature Scoring Functions (implement riêng):**
```php
private function scoreLoaiSuCo($loaiSuCo): int;
private function scoreSoNguoi($soNguoi): int;
private function scoreTuBaoCao($mucDo): int;
private function scoreKhoangCach($lat, $lng): int;
```

**Acceptance Criteria:**
- Score 80-100 → CRITICAL ✓
- Score 60-79 → HIGH ✓
- Score 40-59 → MEDIUM ✓
- Score 0-39 → LOW ✓

**Tests to Write:**
```
- test_score_critical_earthquake()
- test_score_high_flood()
- test_weight_adjustment_impact()
```

---

### Task 1.3: HangDoiXuLyController - Priority Queue
- [ ] Implement `create()` after AI scoring (called from Job):
  - [ ] INSERT `hang_doi_xu_ly` (id_yeu_cau, diem_uu_tien, muc_khan_cap, trang_thai = 'WAITING')
- [ ] Implement `getWaitingQueue()` (list cho Admin):
  - [ ] SELECT hàng đợi ORDER BY `diem_uu_tien DESC, created_at ASC`
  - [ ] JOIN `yeu_cau_cuu_ho`, `loai_su_co`, `nguoi_dung`
  - [ ] Pagination: 20 items/page
  - [ ] Filter by `muc_khan_cap` (optional)
- [ ] Implement `checkAlerts()` method:
  - [ ] CRITICAL đang chờ > 2 giờ → Flag for alert
  - [ ] HIGH đang chờ > 4 giờ → Auto escalate → CRITICAL

**Acceptance Criteria:**
- Queue sorted correctly ✓
- Alert triggered on time thresholds ✓
- Pagination working ✓

**Tests to Write:**
```
- test_queue_ordering_by_score()
- test_alert_critical_timeout()
```

---

### 🔗 Async Job: `ProcessAiScoringJob`
- [ ] Create: `app/Jobs/ProcessAiScoringJob.php`
  - [ ] Queue: `database` or `redis`
  - [ ] Delay: immediate
  - [ ] Handle: call `PhanLoaiAisController::score()`
  - [ ] On failure: log + retry 3 times

---

## 📅 TUẦN 2: Phân Công & Kết Quả (UC08, UC18, UC19)

### Task 2.1: DoiCuuHoController - Team Management
- [ ] Implement `index()`: List tất cả đội với status + capacity:
  - [ ] SELECT `doi_cuu_ho` join `nang_luc_doi`
  - [ ] Response include: `id`, `ten_co`, `trang_thai`, `so_viec_dang_xu_ly`, `so_viec_toi_da`
- [ ] Implement `suggestTeamForRequest()` (gợi ý phân công):
  - [ ] Input: `id_yeu_cau`
  - [ ] Calculate `diem_phu_hop = (0.4 × khoang_cach) + (0.3 × ty_le_hoan_thanh) + (0.2 × dung_loai_su_co) + (0.1 × san_sang)`
  - [ ] Return top 3 teams sorted by `diem_phu_hop DESC`
  - [ ] Validate: `so_viec_dang_xu_ly < so_viec_toi_da` + `loai_su_co` match
- [ ] Implement exception when team unavailable

**Acceptance Criteria:**
- Top 3 teams suggested correctly ✓
- Capacity constraint enforced ✓

**Tests to Write:**
```
- test_suggest_team_by_proximity()
- test_suggest_respects_capacity()
```

---

### Task 2.2: PhanCongCuuHoController - Assignment Workflow
- [ ] Implement `store()` (Admin phân công):
  - [ ] Input: `id_yeu_cau`, `id_doi_cuu_ho`
  - [ ] Validate: đội available, yêu cầu CHO_XU_LY
  - [ ] INSERT `phan_cong_cuu_ho` (trang_thai = 'MOI')
  - [ ] UPDATE `hang_doi_xu_ly` (trang_thai = 'ASSIGNED')
  - [ ] UPDATE `yeu_cau_cuu_ho` (trang_thai = 'DANG_XU_LY')
  - [ ] UPDATE `doi_cuu_ho` (trang_thai = 'DANG_XU_LY')
  - [ ] UPDATE `nang_luc_doi` (so_viec_dang_xu_ly += 1)
  - [ ] Dispatch notification job tới đội
- [ ] Implement `updateStatus()` (cập nhật tiến độ):
  - [ ] Allow transitions: MOI → DANG_XU_LY, MOI → HUY, DANG_XU_LY → HOAN_THANH
  - [ ] On HOAN_THANH: trigger `ProcessAssignmentCompletionJob`
- [ ] Implement `list()`: Show assignments for team

**Acceptance Criteria:**
- Assignment created + notification sent ✓
- Status transition validated ✓
- Capacity updated correctly ✓

**Tests to Write:**
```
- test_assign_task_to_team()
- test_invalid_team_capacity()
- test_task_completion_flow()
```

---

### Task 2.3: KetQuaCuuHoController - Result Submission
- [ ] Implement `store()` (đội nộp kết quả):
  - [ ] Input: `id_phan_cong`, `bao_cao_hien_truong`, `hinh_anh[]`, `thoi_gian_ket_thuc`
  - [ ] Upload hình ảnh → `storage/app/uploads/results/`
  - [ ] INSERT `ket_qua_cuu_ho`
  - [ ] UPDATE `doi_cuu_ho` (set `id_ket_qua` = new result id) - **shortcut reference**
  - [ ] UPDATE `nang_luc_doi` (so_viec_dang_xu_ly -= 1)
  - [ ] Calculate `thoi_gian_xu_ly = TIMEDIFF(now, phan_cong.thoi_gian_phan_cong)`
  - [ ] Update `nang_luc_doi.thoi_gian_xu_ly_tb` (average)
- [ ] Implement `show()`: Get result details by `id_ket_qua`
  - [ ] Include hình ảnh + báo cáo

**Acceptance Criteria:**
- Result saved with images ✓
- Team capacity decremented ✓
- Average time calculated ✓

**Tests to Write:**
```
- test_submit_result_with_images()
- test_team_capacity_after_completion()
```

---

### 🔗 Async Jobs:
- [ ] `SendNotificationJob` - Push notification to team
- [ ] `ProcessAssignmentCompletionJob` - Mark yêu_cầu HOAN_THANH, send rating link
- [ ] Queue các jobs này với priority

---

## 📅 TUẦN 3: Tracking & Heatmap (UC17, UC24)

### Task 3.1: ViTriDoiCuuHoController - GPS Real-time
- [ ] Implement `upsert()` (team update location):
  - [ ] Input: `id_doi_cuu_ho`, `vi_tri_lat`, `vi_tri_lng` (từ mobile app)
  - [ ] UPSERT `vi_tri_doi_cuu_ho` (keep latest)
  - [ ] Validate: coordinates in VN range
  - [ ] Response: OK + timestamp
- [ ] Implement `getTeamLocation()` (Admin view):
  - [ ] Get latest location + updated_at
- [ ] Implement `getTeamTrailLast24h()`:
  - [ ] Return hành trình 24h gần nhất (for debugging)

**Acceptance Criteria:**
- Location updated every sync ✓
- No stale data > 1 hour old ✓

**Tests:**
```
- test_update_team_location()
- test_trail_last_24_hours()
```

---

### Task 3.2: DuLieuHeatmapController - Heatmap Calculation
- [ ] Implement `calculateAndStore()` (Cron job backend):
  - [ ] SELECT `yeu_cau_cuu_ho` từ 24h gần nhất (CHO_XU_LY, DANG_XU_LY)
  - [ ] Group by grid: `ROUND(lat, 2)`, `ROUND(lng, 2)` (~1.1 km)
  - [ ] Calculate `weight = SUM(CASE muc_khan_cap WHEN CRITICAL THEN 4 ...)`
  - [ ] Normalize: `diem_nguy_hiem = weight / COUNT(*)`
  - [ ] UPSERT `du_lieu_heatmap`
- [ ] Implement `getHeatmapData()` (return to Frontend):
  - [ ] SELECT all from `du_lieu_heatmap` (order by diem DESC)
  - [ ] Response format: `[{lat, lng, weight}, ...]`
- [ ] Add Redis caching (cache 5 min)

**Acceptance Criteria:**
- Heatmap aggregated correctly ✓
- Grid size ~1km ✓
- Cached for performance ✓

**Tests:**
```
- test_heatmap_aggregation()
- test_caching_active()
```

---

## 📅 TUẦN 4: Authorization & Optimization (UC01-UC05)

### Task 4.1: Authorization Middleware
- [ ] Create `CheckAdminRole` middleware:
  - [ ] Verify `admin.chuc_vu` in: SIEU_QUAN_TRI, DIEU_PHOI_VIEN, XEM_BAO_CAO
  - [ ] Load permissions từ `phan_quyen` table
  - [ ] Based on `chuc_vu` restrict UC access
- [ ] Create `CheckNguoiDungRole` middleware:
  - [ ] Verify người dùng is `nguoi_dung` (not admin)
- [ ] Create `CheckTeamMemberRole` middleware:
  - [ ] Verify `thanh_vien_doi` status

- [ ] Apply middleware to routes:
  - [ ] `/api/admin/*` → `CheckAdminRole`
  - [ ] `/api/user/*` → `CheckNguoiDungRole`
  - [ ] `/api/team/*` → `CheckTeamMemberRole`

**Acceptance Criteria:**
- Unauthorized requests rejected ✓
- Correct roles allowed ✓

---

### Task 4.2: API Documentation (Swagger/OpenAPI)
- [ ] Install `darkaonuk/l5-swagger`
- [ ] Add OpenAPI annotations to all controllers:
  - [ ] @OA\Get, @OA\Post, @OA\Put, @OA\Delete
  - [ ] Request/response schemas
  - [ ] Status code examples (200, 201, 400, 401, 404, 422, 429)
- [ ] Generate docs: `php artisan l5-swagger:generate`
- [ ] Swagger UI in `/api/docs`

**Acceptance Criteria:**
- All endpoints documented ✓
- Swagger UI accessible ✓

---

### Task 4.3: Performance Optimization
- [ ] Database Indexes:
  - [ ] `yeu_cau_cuu_ho`: (trang_thai, created_at)
  - [ ] `hang_doi_xu_ly`: (diem_uu_tien DESC, trang_thai)
  - [ ] `phan_cong_cuu_ho`: (id_doi_cuu_ho, trang_thai_nhiem_vu)
  - [ ] `vi_tri_doi_cuu_ho`: (id_doi_cuu_ho, updated_at)
- [ ] Eager loading (prevent N+1):
  - [ ] Yêu cầu listing: load loai_su_co, nguoi_dung
  - [ ] Queue listing: load yeu_cau, loai_su_co
- [ ] Redis caching:
  - [ ] Cache team list (5 min)
  - [ ] Cache heatmap data (5 min)
  - [ ] Cache loai_su_co (1 day - static data)

- [ ] Query optimization:
  - [ ] Replace N+1 loops with JOINs
  - [ ] Use select() to fetch only needed columns

**Acceptance Criteria:**
- Indexes created + verified ✓
- Response time < 200ms for list endpoints ✓
- Redis caching active ✓

---

### Task 4.4: Error Handling & Logging
- [ ] Create custom exception classes:
  - [ ] `InvalidCoordinatesException`
  - [ ] `TeamCapacityExceededException`
  - [ ] `RateLimitExceededException`
- [ ] Exception handler: map to proper HTTP status + message
- [ ] Add logging:
  - [ ] All AI scoring attempts (with scores)
  - [ ] All phân công actions
  - [ ] All errors

**Acceptance Criteria:**
- Errors return consistent JSON ✓
- Logging captures key events ✓

---

## ✅ Definition of Done (Week 4)

- [ ] All 21 use cases have API endpoints
- [ ] 80%+ test coverage (PHPUnit)
- [ ] < 200ms response time for list endpoints
- [ ] < 500ms for complex scoring
- [ ] All endpoints documented in Swagger
- [ ] No N+1 queries
- [ ] Database indexes created
- [ ] Error handling consistent
- [ ] Authorization enforced
- [ ] Async jobs working (queued tasks execute)

---

## 🚀 How to Test Each Week

**Week 1**: 
```bash
php artisan test tests/Feature/YeuCauCuuHo*
php artisan test tests/Feature/PhanLoaiAis*
```

**Week 2**:
```bash
php artisan test tests/Feature/PhanCongCuuHo*
php artisan test tests/Feature/KetQuaCuuHo*
```

**Week 3**:
```bash
php artisan test tests/Feature/ViTriDoiCuuHo*
php artisan test tests/Feature/DuLieuHeatmap*
```

**Week 4**:
```bash
php artisan test --coverage  # Show coverage %
curl http://localhost:8000/api/docs  # Check Swagger
```

---

## 📞 Blockers/Dependencies

- ❌ Frontend not ready? Test with Postman/cURL
- ❌ AI weights not finalized? Use defaults, update later
- ❌ File storage not configured? Use local `storage/` first
- ❌ Queue not available? Run jobs synchronously in .env: `QUEUE_CONNECTION=sync`

---

**Last Updated**: 2026-03-31  
**Status**: Not Started  
☐ Week 1 ☐ Week 2 ☐ Week 3 ☐ Week 4 ☐ Complete
