# 🤖 Person 3: AI/ML Engineer

**Role**: Implement AI scoring engine, automated processes, cron jobs  
**Duration**: Tuần 1-3 (Critical path bottleneck)  
**Dependencies**: Backend setup (Person 1), database (✅), TrongSoPhanLoai weights  

---

## 📅 TUẦN 1: AI Scoring Algorithm

### Task 1.1: Setup & Data Exploration
- [ ] Review business logic doc section 4.3 (AI Phân Loại & Chấm Điểm Ưu Tiên)
- [ ] Query existing data:
  ```bash
  php artisan tinker
  > \App\Models\LoaiSuCo::all()
  > \App\Models\TrongSoPhanLoai::all()
  > \App\Models\YeuCauCuuHo::latest()->first()
  ```
- [ ] Understand feature vectors needed:
  - [ ] W1 (0.25) - Loại thiên tai (disaster type)
  - [ ] W2 (0.30) - Số người ảnh hưởng (people affected)
  - [ ] W3 (0.20) - Tự báo cáo (self-assessment)
  - [ ] W4 (0.15) - Thời gian chờ (wait time)
  - [ ] W5 (0.10) - Khu vực nguy hiểm (risky area - TBD)

---

### Task 1.2: Create Scoring Service
- [ ] Create `app/Services/AiScoringService.php`:
  ```php
  class AiScoringService {
    public function scoreRequest(int $idYeuCau): array {
      // Step 1: Gather features
      // Step 2: Normalize to 0-100
      // Step 3: Apply weights
      // Step 4: Classify level
      // Return: [score, level, confidence]
    }
    
    private function scoreLoaiSuCo($loaiSuCo): int {}
    private function scoreSoNguoi($soNguoi): int {}
    private function scoreTuBaoCao($mucDo): int {}
    private function scoreKhoangCach($lat, $lng): int {}
    private function getWeights(): array {}
  }
  ```

**Feature 1 - Loại Sự Cố** (W1 = 0.25):
- Mapping (adjust based on severity):
  ```
  'dong-dat'     → 100 (highest)
  'sat-lo-dat'   → 95
  'bao'          → 85
  'song-than'    → 75
  'lu-lut'       → 70
  'ngap-ung'     → 50
  'han-han'      → 30 (lowest)
  ```
- [ ] Create mapping table/constant in code
- [ ] Query loai_su_co.slug_danh_muc from request

**Feature 2 - Số Người Bị Ảnh Hưởng** (W2 = 0.30):
- [ ] Scoring:
  ```
  soNguoi > 50       → 100
  21-50              → 75
  6-20               → 50
  1-5                → 25
  0                  → 0
  ```

**Feature 3 - Tự Báo Cáo** (W3 = 0.20):
- [ ] Scoring:
  ```
  CRITICAL (4)  → 100
  HIGH (3)      → 75
  MEDIUM (2)    → 50
  LOW (1)       → 25
  ```

**Feature 4 - Thời Gian Chờ** (W4 = 0.15):
- [ ] Tính từ `yeu_cau_cuu_ho.created_at` đến `NOW()`
- [ ] Scoring (tăng dần):
  ```
  > 6 giờ   → 100
  3-6 giờ   → 80
  1-3 giờ   → 50
  < 1 giờ   → 20
  ```

**Feature 5 - Khu Vực Nguy Hiểm** (W5 = 0.10) - **Optional Week 1**:
- [ ] Placeholder: 0 điểm (implement in week 3 with heatmap)

---

### Task 1.3: Classification & Weighting
- [ ] Create weight normalization:
  ```php
  private function normalizeWeights(array $weights): array {
    $sum = array_sum($weights);
    return array_map(fn($w) => $w / $sum, $weights);
  }
  ```
- [ ] Calculate final score:
  ```php
  $diem_uu_tien = (W1 × score_loai)
                + (W2 × score_so_nguoi)
                + (W3 × score_tu_bao_cao)
                + (W4 × score_thoi_gian)
                + (W5 × score_khu_vuc)
  
  // Result: 0-100
  ```

- [ ] Classification function:
  ```php
  private function classifyUrgency($score): string {
    if ($score >= 80)      return 'CRITICAL';
    elseif ($score >= 60)  return 'HIGH';
    elseif ($score >= 40)  return 'MEDIUM';
    else                   return 'LOW';
  }
  ```

---

### Task 1.4: Implement PhanLoaiAisJob
- [ ] Create `app/Jobs/PhanLoaiAisJob.php`:
  - [ ] Queue-able async job
  - [ ] Accepts: `id_yeu_cau`
  - [ ] Calls `AiScoringService::scoreRequest()`
  - [ ] Stores result in `phan_loai_ais` table:
    ```php
    PhanLoaiAis::create([
      'id_yeu_cau' => $idYeuCau,
      'diem_uu_tien' => $score,
      'muc_khan_cap' => $level,
      'do_tin_cay' => 0.95,  // confidence
      'ly_do' => json_encode($features),
      'model_version' => 'v1.0'
    ]);
    ```
  - [ ] UPDATE `yeu_cau_cuu_ho` (diem_uu_tien, muc_do_khan_cap)
  - [ ] Error handling: retry 3 times

- [ ] Register job in `/api.php` or config/queue.php

**Acceptance Criteria:**
- Job runs without errors ✓
- Score calculated correctly ✓
- Stored in database ✓

---

### Task 1.5: Test Suite for Scoring
- [ ] Create `tests/Unit/AiScoringServiceTest.php`:
  ```php
  public function test_score_critical_earthquake() {
    // CRITICAL earthquake, 100 people → score should be 85-100
  }
  
  public function test_score_low_drought() {
    // Low urgency drought → score should be 20-40
  }
  
  public function test_weight_application() {
    // Test weights from TrongSoPhanLoai
  }
  
  public function test_classification_accuracy() {
    // Test all 4 thresholds
  }
  ```
- [ ] Run tests:
  ```bash
  php artisan test tests/Unit/AiScoringServiceTest.php
  ```

**Target Coverage**: 100% for scoring logic

---

## 📅 TUẦN 2: Cron Jobs & Automated Processes

### Task 2.1: Laravel Scheduler Setup
- [ ] Configure `config/queue.php`:
  - [ ] QUEUE_CONNECTION = redis / database (not sync)
  - [ ] QUEUE_DRIVER = redis / database
- [ ] Setup `app/Console/Kernel.php`:
  ```php
  protected function schedule(Schedule $schedule) {
    // Week 2 tasks here
  }
  ```
- [ ] Test scheduler locally:
  ```bash
  php artisan schedule:run  # test once
  php artisan schedule:work  # local dev (mimics cron)
  ```

---

### Task 2.2: Tái Tính Điểm (Cron - mỗi 30 phút)
- [ ] Create `app/Console/Commands/RecalculatePriorityScores.php`:
  - [ ] Find all `hang_doi_xu_ly` with trang_thai = 'WAITING'
  - [ ] For each, call `AiScoringService::scoreRequest()`
  - [ ] UPDATE `hang_doi_xu_ly` with new `diem_uu_tien`
  - [ ] INSERT new `phan_loai_ais` record (audit trail)
  - [ ] Optimize: batch update (not loop)

- [ ] Register in Kernel:
  ```php
  $schedule->command('app:recalculate-scores')
           ->everyThirtyMinutes()
           ->withoutOverlapping();
  ```

- [ ] Logging:
  ```php
  Log::info("Recalculated scores for 50 waiting requests");
  Log::warning("CRITICAL request waiting > 2 hours: request#123");
  ```

**Acceptance Criteria:**
- Command runs every 30 mins ✓
- Scores updated correctly ✓
- Logging captured ✓

---

### Task 2.3: Auto-Escalation (Cron - mỗi 15 phút)
- [ ] Create `app/Console/Commands/CheckEscalationAlerts.php`:
  - [ ] Find: CRITICAL waiting > 2 hours
  - [ ] Find: HIGH waiting > 4 hours
  - [ ] For HIGH → CRITICAL escalation:
    ```php
    foreach ($highRequests as $req) {
      $waitMinutes = $req->created_at->diffInMinutes();
      if ($waitMinutes > 240) {
        // Update to CRITICAL
        $req->yeu_cau_cuu_ho->update([
          'muc_do_khan_cap' => 'CRITICAL'
        ]);
        // Log alert
        Log::warning("Auto-escalated request#$req->id to CRITICAL");
        // Dispatch notification (week 4)
      }
    }
    ```

- [ ] Register in Kernel:
  ```php
  $schedule->command('app:check-escalation')
           ->everyFifteenMinutes()
           ->withoutOverlapping();
  ```

**Acceptance Criteria:**
- Escalation triggers correctly ✓
- High → CRITICAL after 4h ✓
- Logging captures alerts ✓

---

### Task 2.4: Distance Calculation (Haversine)
- [ ] Create `app/Utils/DistanceCalculator.php`:
  ```php
  class DistanceCalculator {
    public static function haversine(
      float $lat1, float $lng1,
      float $lat2, float $lng2
    ): float {
      // Haversine formula → distance in km
      return $distance;
    }
  }
  ```

- [ ] Test with known coordinates:
  ```
  Hà Nội (21.0285, 105.8542) → HCM (10.7769, 106.7009)
  Distance ≈ 1,147 km
  ```

- [ ] Use for team assignment scoring (week 2, Person 1)
- [ ] Later: use for heatmap grid calculations

**Acceptance Criteria:**
- Haversine accurate ± 5% ✓
- Fast (<10ms per calc) ✓

---

## 📅 TUẦN 3: Heatmap & Optimization

### Task 3.1: Heatmap Aggregation (Cron - mỗi 15 phút)
- [ ] Create `app/Console/Commands/GenerateHeatmapData.php`:
  - [ ] Query `yeu_cau_cuu_ho` from last 24 hours:
    ```sql
    WHERE created_at >= NOW() - INTERVAL 24 HOUR
      AND trang_thai IN ('CHO_XU_LY', 'DANG_XU_LY', 'HOAN_THANH')
    ```
  - [ ] Group by grid: `ROUND(vi_tri_lat, 2)`, `ROUND(vi_tri_lng, 2)`
  - [ ] For each grid cell calculate:
    ```php
    $weight = SUM(CASE muc_do_khan_cap
      WHEN 'CRITICAL' THEN 4
      WHEN 'HIGH'     THEN 3
      WHEN 'MEDIUM'   THEN 2
      WHEN 'LOW'      THEN 1
      ELSE 0
    END)
    
    $diem_nguy_hiem = $weight / count($requests)  // avg intensity
    ```
  - [ ] UPSERT `du_lieu_heatmap`:
    ```php
    DuLieuHeatmap::updateOrCreate(
      ['vi_tri_lat' => $gridLat, 'vi_tri_lng' => $gridLng],
      [
        'mat_do' => $count,
        'diem_nguy_hiem' => $diem,
        'updated_at' => now()
      ]
    );
    ```

- [ ] Register in Kernel:
  ```php
  $schedule->command('app:generate-heatmap')
           ->everyFifteenMinutes()
           ->withoutOverlapping();
  ```

- [ ] Optimize:
  - [ ] Use raw DB query (not ORM) for speed
  - [ ] Batch upsert (not loop)
  - [ ] Clean old data: delete > 24h old

**Acceptance Criteria:**
- Heatmap updates every 15 min ✓
- Grid size ~1km² ✓
- Intensity calculated correctly ✓

---

### Task 3.2: Feature 5 - Khu Vực Nguy Hiểm (Risk Area)
- [ ] Use heatmap data to score risk area:
  ```php
  private function scoreKhuVucNguyHiem($lat, $lng): int {
    // Find nearest grid cell in du_lieu_heatmap
    $nearestCell = DuLieuHeatmap::nearestCell($lat, $lng);
    
    if (!$nearestCell) return 0;  // No data
    
    // Map diem_nguy_hiem (0-100) directly as score
    return (int) $nearestCell->diem_nguy_hiem;
  }
  ```

- [ ] Update `AiScoringService` to include W5:
  - [ ] Feature 5 score = khu vực nguy hiểm (from heatmap)
  - [ ] Retest scoring with all 5 features

**Acceptance Criteria:**
- Feature 5 integrated ✓
- Heatmap used in scoring ✓
- Retest shows correct weights ✓

---

### Task 3.3: Audit Trail & Explainability
- [ ] `phan_loai_ais.ly_do` stores full feature breakdown:
  ```json
  {
    "features": {
      "loai_su_co": {"slug": "dong-dat", "score": 100},
      "so_nguoi": {"count": 150, "score": 100},
      "tu_bao_cao": {"level": "CRITICAL", "score": 100},
      "thoi_gian": {"minutes": 45, "score": 50},
      "khu_vuc": {"lat": 10.75, "lng": 106.67, "score": 35}
    },
    "weights": [0.25, 0.30, 0.20, 0.15, 0.10],
    "final_score": 85,
    "model_version": "v1.0"
  }
  ```

- [ ] API endpoint to explain score:
  - [ ] GET `/api/admin/phan-loai/{id}/explain`
  - [ ] Return: formatted feature breakdown + visualization hints

**Acceptance Criteria:**
- Audit trail captured ✓
- Explain endpoint works ✓

---

### Task 3.4: Performance Testing
- [ ] Simulate 1000 requests coming in simultaneously:
  ```bash
  # Use Laravel's factory
  YeuCauCuuHo::factory(1000)->create();
  
  # Measure scoring time
  $start = microtime(true);
  foreach ($requests as $req) {
    $service->scoreRequest($req->id);
  }
  $elapsed = microtime(true) - $start;
  // Should be < 10 seconds for 1000
  ```

- [ ] Optimize:
  - [ ] Use database query caching (Redis)
  - [ ] Batch process instead of loop
  - [ ] Profile: identify bottleneck
  - [ ] Add indexes if needed

**Target**: < 5ms per request

**Acceptance Criteria:**
- 1000 requests scored in < 5 seconds ✓
- No memory leaks ✓

---

### Task 3.5: Documentation & Handoff
- [ ] Write `AI_SCORING_DOCUMENTATION.md`:
  - [ ] Algorithm overview
  - [ ] Feature descriptions
  - [ ] Weight explanations
  - [ ] Classification thresholds
  - [ ] How to adjust weights via admin
  - [ ] Examples with calculations
  - [ ] Performance metrics

- [ ] Create admin UI for weight adjustment:
  - [ ] GET `/api/admin/trong-so-phan-loai` (list)
  - [ ] PUT `/api/admin/trong-so-phan-loai/{id}` (update)
  - [ ] Validation: sum must = 1.0

**Acceptance Criteria:**
- Documentation complete ✓
- Admin can adjust weights ✓
- Changes take effect immediately ✓

---

## ✅ Definition of Done (Week 3)

- [ ] All 5 features scoring correctly
- [ ] AI scoring service 100% test coverage
- [ ] 3 cron jobs configured (recalc, escalation, heatmap)
- [ ] Performance: < 5ms per request
- [ ] Heatmap updating every 15 min
- [ ] Audit trail complete
- [ ] Admin explain endpoint works
- [ ] Documentation written
- [ ] No N+1 queries
- [ ] Weights configurable by admin

---

## 🧪 Testing Checklist

**Unit Tests:**
- [ ] `scoreLoaiSuCo()` - all 7 types
- [ ] `scoreSoNguoi()` - all buckets
- [ ] `scoreTuBaoCao()` - all 4 levels
- [ ] `scoreKhoangCach()` - time ranges
- [ ] `classifyUrgency()` - all 4 thresholds

**Integration Tests:**
- [ ] Full scoring workflow
- [ ] Database save
- [ ] Cron execution

**Load Tests:**
- [ ] 100 concurrent requests
- [ ] 1000 sequential request scoring

**Edge Cases:**
- [ ] NULL values
- [ ] Invalid coordinates
- [ ] Missing features
- [ ] Weight sum != 1.0

---

## 📊 Metrics to Track

**Scoring Quality:**
- Average score per urgency level (should cluster)
- Distribution histogram
- Manual validation (spot check 50 cases)

**Performance:**
- P95 latency
- P99 latency
- Max memory per job

**System:**
- False positive rate (manual review)
- Admin weight adjustment impact
- Heatmap data freshness

---

**Last Updated**: 2026-03-31  
**Status**: Not Started  
☐ Week 1 ☐ Week 2 ☐ Week 3 ☐ Complete
