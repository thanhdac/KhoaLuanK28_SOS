# 🎨 Person 2: Frontend Developer

**Role**: VueJS UI/UX cho 3 actors (người dân, admin, đội cứu hộ)  
**Duration**: Tuần 1-4 (Blockchain: UI depends on API)  
**Dependencies**: Backend API (Person 1), Authentication setup ✅  

---

## 📅 TUẦN 1: Setup & Foundation

### Task 1.1: Project Setup & Structure
- [ ] Initialize Vue 3 + TypeScript + Vite (check current setup)
- [ ] Install dependencies:
  - [ ] `vue-router` - routing
  - [ ] `pinia` - state management
  - [ ] `axios` - HTTP client
  - [ ] `tailwindcss` - styling
  - [ ] `chart.js` + `vue-chartjs` - charts
  - [ ] `leaflet` + `vue2-leaflet` - maps
  - [ ] `pinia-persist` - persist state
- [ ] Create folder structure:
  ```
  src/
  ├── components/        # Shared components
  ├── views/             # Page components
  ├── stores/            # Pinia stores
  ├── services/          # API clients
  ├── types/             # TypeScript types
  ├── utils/             # Helpers
  ├── assets/
  └── layouts/           # Layout wrappers
  ```
- [ ] Setup `.env` files:
  - [ ] `.env.production`: backend URL
  - [ ] `.env.development`: localhost backend

**Acceptance Criteria:**
- Vue app runs without errors ✓
- TailwindCSS works ✓
- Folder structure clean ✓

---

### Task 1.2: Authentication Store & Services
- [ ] Create `stores/authStore.ts` (Pinia):
  - [ ] State: `user`, `token`, `role` (nguoi_dung | admin | team)
  - [ ] Actions: `login()`, `logout()`, `checkToken()`, `setRole()`
  - [ ] Persist to localStorage (token + user)
- [ ] Create `services/authService.ts`:
  - [ ] `loginAsUser(email, password)` → POST `/api/user/login`
  - [ ] `loginAsAdmin(email, password)` → POST `/api/admin/login`
  - [ ] `logout()`
  - [ ] `getCurrentUser()` → GET `/api/[role]/profile`
  - [ ] `refreshToken()` (if needed)
- [ ] Setup axios interceptor:
  - [ ] Auto-add Authorization header
  - [ ] Handle 401 → redirect to login
  - [ ] Handle 429 → show rate limit alert

**Acceptance Criteria:**
- Login flow works ✓
- Token persists ✓
- Auto-refresh on app load ✓

---

### Task 1.3: Layout & Navigation
- [ ] Create main `layouts/MainLayout.vue`:
  - [ ] Header: logo + user menu + logout
  - [ ] Sidebar/Navigation: role-based menu
  - [ ] Footer: copyright
  - [ ] Responsive: hamburger menu on mobile
- [ ] Setup Vue Router:
  - [ ] `/login` - public
  - [ ] `/user/*` - protected (role = nguoi_dung)
  - [ ] `/admin/*` - protected (role = admin)
  - [ ] `/team/*` - protected (role = team)
  - [ ] Meta guards: check role on route navigation
- [ ] Create shared components:
  - [ ] `Button.vue` - reusable button (variants: primary, danger, etc)
  - [ ] `Card.vue` - card container
  - [ ] `Modal.vue` - modal dialog
  - [ ] `Toast.vue` - notifications
  - [ ] `Loading.vue` - spinner
  - [ ] `FormField.vue` - label + input wrapper

**Acceptance Criteria:**
- Navigation works across roles ✓
- Login redirects unauthenticated ✓
- Responsive on mobile ✓

---

### Task 1.4: API Client Services
- [ ] Create base `services/api.ts`:
  - [ ] axios instance with interceptors
  - [ ] Error handling
  - [ ] Request/response logging (dev mode)
- [ ] Create service classes:
  - [ ] `YeuCauService` - UC03 (create request)
  - [ ] `QueueService` - hàng đợi
  - [ ] `DoiService` - team management
  - [ ] `HeatmapService` - heatmap data
  - [ ] `DanhGiaService` - rating
- [ ] Each service has TypeScript interfaces:
  ```typescript
  interface YeuCau {
    id_yeu_cau: number;
    id_loai_su_co: number;
    vi_tri_lat: number;
    vi_tri_lng: number;
    trang_thai: 'CHO_XU_LY' | 'DANG_XU_LY' | 'HOAN_THANH';
    diem_uu_tien: number;
  }
  ```

**Acceptance Criteria:**
- API calls work with correct headers ✓
- Errors handled gracefully ✓
- TypeScript types enforced ✓

---

## 📅 TUẦN 2: Người Dân Interface

### Task 2.1: User Dashboard
- [ ] Create `views/user/Dashboard.vue`:
  - [ ] Show user profile: name, phone, email
  - [ ] Quick stats: số yêu cầu hôm nay, number pending, completed
  - [ ] Recent requests list (last 5):
    - [ ] Table: request ID, type, status, time, action
    - [ ] Status badge: CHO_XU_LY (yellow), DANG_XU_LY (blue), HOAN_THANH (green)
    - [ ] Action: view detail button
  - [ ] Create new request button → `/user/request/new`

**Acceptance Criteria:**
- Dashboard loads user data ✓
- Recent requests display correctly ✓
- Responsive on mobile ✓

---

### Task 2.2: Create Request Form
- [ ] Create `views/user/CreateRequest.vue`:
  - [ ] Form fields:
    - [ ] **Location**: 
      - [ ] Map picker (Leaflet) to select coordinates
      - [ ] OR manual input: latitude + longitude fields
      - [ ] Display address text input (optional for notes)
    - [ ] **Disaster Type**: Dropdown (fetch from API `/api/loai-su-co`)
      - [ ] Show icon/emoji for each type
    - [ ] **People Affected**: number input (1-9999)
    - [ ] **Urgency Self-Assess**: radio (LOW/MEDIUM/HIGH/CRITICAL)
      - [ ] Show visual indicators
    - [ ] **Description**: textarea
    - [ ] **Images**: multi-file upload (max 5 files, 5MB each)
      - [ ] Show file preview thumbnails
      - [ ] Drag-drop support
  - [ ] Form validation:
    - [ ] Location required ✓
    - [ ] Disaster type required ✓
    - [ ] At least 1 image (optional?)
  - [ ] Submit button:
    - [ ] Loading state while uploading
    - [ ] Show progress (especially for files)
    - [ ] Success → redirect to dashboard with toast message
    - [ ] Error → show error message in form

**Component Breakdown:**
- [ ] `LocationPicker.vue` - Map + manual input
- [ ] `DisasterTypeSelect.vue` - Dropdown with icons
- [ ] `FileUpload.vue` - File input with preview

**Acceptance Criteria:**
- Form validation works ✓
- File upload possible ✓
- GPS coordinates captured ✓
- Success message on submit ✓

---

### Task 2.3: Request Detail & Status Tracking
- [ ] Create `views/user/RequestDetail.vue`:
  - [ ] Header: Request ID, type, created time
  - [ ] Status timeline:
    - [ ] CHO_XU_LY → AI scoring...
    - [ ] ASSIGNED → Team assigned (show team name)
    - [ ] DANG_XU_LY → Team on way (show location? optional)
    - [ ] HOAN_THANH → Completed (show team result summary)
  - [ ] Map: show request location + team location (if DANG_XU_LY)
  - [ ] Details panel:
    - [ ] Disaster type, location, people affected
    - [ ] Images gallery
    - [ ] Description
    - [ ] Priority score (if available)
  - [ ] Team info (if assigned):
    - [ ] Team name, vehicle, contact, current location

**Components:**
- [ ] `StatusTimeline.vue` - vertical timeline
- [ ] `RequestMap.vue` - Leaflet map with markers

**Acceptance Criteria:**
- Timeline updates correctly ✓
- Map shows locations ✓
- Mobile responsive ✓

---

### Task 2.4: Rating & Feedback
- [ ] Create `views/user/RateRequest.vue`:
  - [ ] Only accessible after request HOAN_THANH
  - [ ] Star rating (1-5):
    - [ ] Clickable stars, hover effect
    - [ ] Show numeric score
  - [ ] Comment textarea
  - [ ] Submit button:
    - [ ] POST to `/api/danh-gia` with `id_yeu_cau`
    - [ ] Show success message
    - [ ] Redirect to dashboard

**Acceptance Criteria:**
- Rating form works ✓
- Only for completed requests ✓

---

### Task 2.5: User Profile
- [ ] Create `views/user/Profile.vue`:
  - [ ] Edit profile: name, phone, email
  - [ ] Change password form
  - [ ] View history: all requests (paginated)
  - [ ] Logout button

**Acceptance Criteria:**
- Profile editable ✓
- History paginated ✓

---

## 📅 TUẦN 3: Admin Dashboard Interface

### Task 3.1: Admin Main Dashboard
- [ ] Create `views/admin/Dashboard.vue`:
  - [ ] **KPI Cards** (real-time):
    - [ ] Total requests today (query `/api/admin/stats/today`)
    - [ ] CRITICAL waiting (count)
    - [ ] Teams available
    - [ ] Completion rate (%)
  - [ ] **Line Chart**: Requests/day last 7 days
  - [ ] **Pie Chart**: Distribution by urgency level (CRITICAL/HIGH/MEDIUM/LOW)
  - [ ] **Bar Chart**: Team efficiency (completion rate %)
  - [ ] **Top 5 Areas**: Map with markers showing hotspots
  - [ ] Refresh button + auto-refresh toggle (30 sec)

**Components:**
- [ ] `KPICard.vue` - stat card
- [ ] `ChartContainer.vue` - wrapper for charts

**Acceptance Criteria:**
- KPIs update ✓
- Charts render correctly ✓
- Responsive on desktop ✓

---

### Task 3.2: Queue Management
- [ ] Create `views/admin/QueueManagement.vue`:
  - [ ] **Queue List** (GET `/api/admin/hang-doi-xu-ly`):
    - [ ] Table: Request ID, Type, Location, People, Urgency, Wait Time, Priority Score
    - [ ] Color-code urgency (CRITICAL=red, HIGH=orange, MEDIUM=yellow, LOW=green)
    - [ ] Sort by: Priority Score (default), Type, Wait Time
    - [ ] Filter by: Urgency level
    - [ ] Pagination: 15 items/page
  - [ ] **Actions**:
    - [ ] Click row → assign team (modal)
    - [ ] View request detail (new tab)
  - [ ] **Alerts**:
    - [ ] Red banner if CRITICAL > 2 hours waiting
    - [ ] Animation/pulse on these rows

**Components:**
- [ ] `QueueTable.vue` - main table
- [ ] `AssignTeamModal.vue` - assignment modal
- [ ] `UrgencyBadge.vue` - colored badge

**Acceptance Criteria:**
- Queue loads and sorts ✓
- Assignment modal works ✓
- Alerts visible ✓

---

### Task 3.3: Assignment & Team Dispatch
- [ ] Create `views/admin/AssignRequest.vue`:
  - [ ] **Request Details Panel** (left):
    - [ ] Map with request location marker
    - [ ] Request info (type, location, people, images)
  - [ ] **Team Suggestions Panel** (right):
    - [ ] List top 3 teams with matching score
    - [ ] Each team card show:
      - [ ] Team name, vehicle type
      - [ ] Distance & ETA (calculated)
      - [ ] Completion rate %
      - [ ] Current status
    - [ ] Assign button on each card
  - [ ] **Assigned Teams List**:
    - [ ] Show if request already assigned + team details
    - [ ] Reassign button (override)

**Components:**
- [ ] `TeamSuggestionCard.vue` - team option card
- [ ] `AssignmentPanel.vue` - full component

**Acceptance Criteria:**
- Teams suggested correctly ✓
- Assignment saves ✓
- Team gets notification ✓

---

### Task 3.4: Heatmap View
- [ ] Create `views/admin/Heatmap.vue`:
  - [ ] **Leaflet Map** with heatmap layer:
    - [ ] GET `/api/admin/heatmap-data`
    - [ ] Gradient color: Red (high danger) → Yellow → Green (low)
    - [ ] Circle overlay on grid cells, size = intensity
  - [ ] **Overlay Controls** (checkbox toggles):
    - [ ] [ ] Heatmap layer
    - [ ] [ ] Pending requests (red pins)
    - [ ] [ ] In-progress (blue pins)
    - [ ] [ ] Team locations (green trucks)
    - [ ] [ ] Estimated travel paths
  - [ ] **Legend**: explain color coding
  - [ ] **Info Panel**: click on cell/marker to see details

**Libraries:**
- [ ] `leaflet.heat` for heatmap
- [ ] `leaflet-routing-machine` for routes (optional tuần này)

**Acceptance Criteria:**
- Heatmap displays ✓
- Layers toggleable ✓
- Responsive ✓

---

### Task 3.5: Team & Resource Management
- [ ] Create `views/admin/TeamManagement.vue`:
  - [ ] **Teams Table**:
    - [ ] Columns: Name, Status (SAN_SANG/DANG_XU_LY), Current Jobs, Max Capacity, Completion Rate
    - [ ] Color status badges
    - [ ] Click → detail view
  - [ ] **Detail View**:
    - [ ] Team info edit form
    - [ ] Team members list (thanh_vien_doi)
    - [ ] Resources list (tai_nguyen_cuu_ho)
    - [ ] Performance history (chart)
    - [ ] Enable/Disable team button
  - [ ] **Add Team** button → form

**Acceptance Criteria:**
- Teams listed ✓
- Detail view works ✓
- Editable ✓

---

### Task 3.6: Reports & Analytics
- [ ] Create `views/admin/Reports.vue`:
  - [ ] **Date Range Picker**: select period
  - [ ] **Tabs**:
    - [ ] **Summary**: Total requests, avg response time, completion rate
    - [ ] **By Type**: Chart showing disaster type distribution
    - [ ] **Team Performance**: Table with stats per team
    - [ ] **Geographic**: Map heatmap of completed requests
  - [ ] **Export Button**: Download CSV/PDF (backend handles generation)

**Acceptance Criteria:**
- Reports load ✓
- Charts display ✓
- Export works ✓

---

## 📅 TUẦN 4: Đội Cứu Hộ Interface & Polish

### Task 4.1: Team Member Dashboard
- [ ] Create `views/team/Dashboard.vue`:
  - [ ] **Current Task** (if assigned):
    - [ ] Request details (location, type, description, images)
    - [ ] Map with navigation (to/from request location)
    - [ ] Assigned time & Estimated completion
    - [ ] Action buttons: Start, Arrive, In Progress, Complete
  - [ ] **Task History** (last 5):
    - [ ] List completed tasks with status
    - [ ] Click to view detail/result
  - [ ] **Team Info**:
    - [ ] Team name, members, capacity
    - [ ] Performance metrics

**Acceptance Criteria:**
- Current task shows ✓
- Navigation ready ✓
- Task actions work ✓

---

### Task 4.2: GPS Tracking & Updates
- [ ] Create `services/gpsService.ts`:
  - [ ] Get user's current location (Geolocation API)
  - [ ] Auto-send GPS every 60 secs (when task active)
  - [ ] POST to `/api/team/vi-tri-update`
  - [ ] Show last updated time
- [ ] Create `views/team/TrackingScreen.vue`:
  - [ ] Map showing:
    - [ ] Current team location (marker + accuracy circle)
    - [ ] Request location (pin)
    - [ ] Estimated route (optional tuần này)
  - [ ] Status panel:
    - [ ] Distance to destination
    - [ ] Estimated arrival time
    - [ ] Speed indicator
  - [ ] Action buttons: Arrived, Submit Result

**Acceptance Criteria:**
- GPS working ✓
- Updates visible ✓
- Map responsive ✓

---

### Task 4.3: Result Submission
- [ ] Create `views/team/SubmitResult.vue`:
  - [ ] Form fields:
    - [ ] **Field Report** textarea (required)
    - [ ] **Images** multi-upload (required, min 1)
    - [ ] **Status** radio (HOAN_THANH / HUY)
    - [ ] If HUY: reason textarea (required)
    - [ ] **Completion Time** auto-filled (or editable)
  - [ ] Image upload:
    - [ ] Drag-drop + click
    - [ ] Preview thumbnails
    - [ ] Delete option
  - [ ] Submit:
    - [ ] POST to `/api/team/ket-qua`
    - [ ] Show success message
    - [ ] Task cleared from current
    - [ ] Return to dashboard

**Acceptance Criteria:**
- Form validation works ✓
- Images uploaded ✓
- Success feedback ✓

---

### Task 4.4: Polish & Accessibility
- [ ] Dark mode toggle (Pinia store + TailwindCSS)
- [ ] Language toggle (i18n - Vietnamese/English) - optional
- [ ] Accessibility:
  - [ ] Semantic HTML
  - [ ] ARIA labels on interactive elements
  - [ ] Keyboard navigation
  - [ ] Color contrast WCAG AA
- [ ] Mobile optimization:
  - [ ] Test on iPhone + Android
  - [ ] Touch-friendly buttons (min 44px)
  - [ ] Viewport meta tag
- [ ] Performance:
  - [ ] Lazy load images
  - [ ] Code splitting for routes
  - [ ] Minify assets

**Acceptance Criteria:**
- Dark mode works ✓
- Mobile usable ✓
- Lighthouse score > 80 ✓

---

### Task 4.5: Error Handling & Edge Cases
- [ ] Handle offline mode:
  - [ ] Queue API calls when offline
  - [ ] Sync when back online
  - [ ] Show "offline" indicator
- [ ] Handle network errors:
  - [ ] Retry logic for failed requests
  - [ ] User-friendly error messages
- [ ] Handle missing data:
  - [ ] Show skeletons while loading
  - [ ] Fallback UI if API fails
- [ ] Session expiry:
  - [ ] Auto-logout on token expiry
  - [ ] Prompt to re-login
  - [ ] Preserve unsaved form data

**Acceptance Criteria:**
- All error states handled ✓
- Loading states visible ✓

---

## ✅ Definition of Done (Week 4)

- [ ] All 3 interfaces built (user, admin, team)
- [ ] 20+ pages/views created
- [ ] API integration complete
- [ ] Responsive design (mobile, tablet, desktop)
- [ ] Authentication flow working
- [ ] TypeScript strict mode
- [ ] No console errors/warnings
- [ ] Performance: lighthouse > 80
- [ ] Accessibility: WCAG AA compliant
- [ ] Dark mode working
- [ ] Tested on real mobile devices

---

## 🎨 Design System / Component Library

**Buttons:**
- Primary (blue) - main actions
- Secondary (gray) - cancel/back
- Danger (red) - delete/abort
- Success (green) - confirm

**Colors:**
- Primary: #3B82F6 (blue)
- Success: #10B981 (green)
- Warning: #F59E0B (amber)
- Danger: #EF4444 (red)
- Critical: #DC2626 (dark red)

**Spacing & Sizing:**
- Use TailwindCSS utilities (4px grid)

**Typography:**
- Heading 1: 2xl font-bold
- Heading 2: xl font-bold
- Body: base font-normal
- Small: sm font-normal

---

## 🔧 How to Debug

**API not responding?**
```bash
# Check in browser console
console.log(axios.defaults.headers.common['Authorization'])
# Should show: "Bearer xxx"
```

**CSS not working?**
```bash
npm run dev  # rebuild Vite
# Check if TailwindCSS is in tailwind.config.js
```

**Type errors?**
```bash
npx tsc --noEmit  # check TS compilation
```

---

**Last Updated**: 2026-03-31  
**Status**: Not Started  
☐ Week 1 ☐ Week 2 ☐ Week 3 ☐ Week 4 ☐ Complete
