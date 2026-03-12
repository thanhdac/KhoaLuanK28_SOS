# API Testing Guide

## 📋 Contents

This folder contains comprehensive test cases for the Rescue Management API:

1. **postman_collection.json** - Full Postman collection with 40+ test cases
2. **POSTMAN_TEST_GUIDE.md** - Detailed guide with request/response examples
3. **curl_test_commands.sh** - cURL commands for command-line testing

---

## 🚀 Quick Start

### Option 1: Postman (Recommended)

**Step 1: Import Collection**
```
1. Open Postman
2. Click "Import" button
3. Select "postman_collection.json" from this project
4. Collection will be imported automatically
```

**Step 2: Run Tests**
```
1. Navigate to Authentication folder
2. Run "Admin Login" test first
3. This will populate all necessary tokens automatically
4. Run remaining tests in order
```

**Step 3: Use Collection Runner**
```
1. Click "..." menu on collection
2. Select "Run Collection"
3. Watch all tests execute with live results
```

---

### Option 2: Command Line (cURL)

**Step 1: View All Commands**
```bash
bash curl_test_commands.sh
```

**Step 2: Get Admin Token**
```bash
TOKEN=$(curl -s -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "admin123"}' | jq -r ".access_token")

echo "Token: $TOKEN"
```

**Step 3: Use Token in Requests**
```bash
curl -X GET http://localhost:8000/api/yeu-cau-cuu-ho \
  -H "Authorization: Bearer $TOKEN"
```

---

### Option 3: Manual Testing with Postman UI

**Create a new request:**
```
1. New → HTTP Request
2. Set method to GET
3. URL: http://localhost:8000/api/yeu-cau-cuu-ho
4. Headers:
   - Authorization: Bearer YOUR_TOKEN_HERE
5. Send
```

---

## 📂 File Descriptions

### postman_collection.json

**Contains:**
- 40+ test cases organized in folders
- Pre-configured requests with sample data
- Automatic variable population
- Test scripts with assertions
- Error case tests

**Folders:**
1. Authentication (2 tests)
2. Help Requests CRUD (5 tests)
3. Rescue Teams (2 tests)
4. Task Assignment Workflow (3 tests)
5. Rescue Results (1 test)
6. Ratings & Feedback (1 test)
7. Statistics & Analytics (4 tests)
8. Filtering & Searching (2 tests)
9. Error Cases & Validation (3 tests)

**Key Features:**
- Auto-populates tokens after login
- Saves IDs for subsequent requests
- Validates response status codes
- Checks response body structure
- Tests business logic

---

### POSTMAN_TEST_GUIDE.md

**Contains:**
- Setup instructions
- Detailed test case descriptions
- Request/response JSON examples
- Test assertion details
- Sample test data
- Running instructions

**Sections:**
1. Setup Instructions
2. Authentication Tests
3. Help Requests API Tests
4. Rescue Teams Tests
5. Task Assignment Workflow
6. Statistics & Analytics
7. Error Cases
8. Key Test Data

**Usage:**
- Reference before creating manual tests
- See expected responses for each endpoint
- Understand test data structure

---

### curl_test_commands.sh

**Contains:**
- 30+ curl command examples
- Color-coded output
- Quick workflow commands
- Sample test data reference

**Sections:**
1. Authentication
2. Help Requests CRUD
3. Rescue Teams
4. Task Assignment
5. Rescue Results
6. Ratings
7. Statistics
8. Quick Test Workflow
9. Sample Test Data

**Usage:**
```bash
# View all commands
bash curl_test_commands.sh

# Or copy individual commands and modify
curl -X GET http://localhost:8000/api/yeu-cau-cuu-ho \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🧪 Test Coverage

### API Endpoints Tested (40+ tests)

**Authentication (2)**
- Admin login
- User login

**Help Requests (5)**
- Get all requests
- Get by ID
- Create new request
- Update status
- Filter by status

**Rescue Teams (2)**
- Get all teams
- Get team with details (members, resources)

**Task Assignment (3)**
- Create assignment
- Get assignment details
- Update assignment status

**Rescue Results (1)**
- Create rescue result

**Ratings (1)**
- Create rating/feedback

**Statistics (4)**
- Total requests count
- Requests by status
- Requests by urgency
- Processing queue status

**Search & Filter (2)**
- Filter by urgency
- Search with keywords

**Error Cases (3)**
- Invalid ID (404)
- Missing fields (422)
- Unauthorized (401)

---

## 📊 Sample Test Data

### Admin Accounts
```
Email: admin@example.com
Password: admin123
```

### User Accounts
```
Email: huong1@example.com | Password: user123
Email: hung1@example.com  | Password: user123
Email: hoa1@example.com   | Password: user123
```

### Incident Types
```
1 = Cháy (Fire)
2 = Đuối nước (Drowning)
3 = Tai nạn giao thông (Traffic)
4 = Sập nhà (Collapse)
5 = Ngạch độc (Poisoning)
6 = Ngộ độc (Intoxication)
7 = Hiểm họa điện (Electrical hazard)
8 = Thương tích (Injury)
```

### Rescue Teams
```
ID 1 = Đội Cứu Hộ Q1
ID 2 = Đội Cứu Hộ Q3
ID 3 = Đội Cứu Hộ Q5
ID 4 = Đội Cứu Hộ Q7
ID 5 = Đội Cứu Hộ Q11
```

### Status Values
```
CHO_XU_LY    = Waiting for processing
DANG_XU_LY   = Currently processing
HOAN_THANH   = Completed
HUY_BO       = Cancelled
```

### Urgency Levels
```
LOW      = Low urgency
MEDIUM   = Medium urgency
HIGH     = High urgency
CRITICAL = Critical urgency
```

---

## 🔑 Key Workflow Tests

### Complete Rescue Management Workflow
```
1. Login as Admin/User
   ↓
2. Create Help Request
   ↓
3. View Request Status (CHO_XU_LY)
   ↓
4. Admin updates to DANG_XU_LY
   ↓
5. Admin creates assignment for team
   ↓
6. Team completes assignment (HOAN_THANH)
   ↓
7. Create rescue result
   ↓
8. User rates rescue service
   ↓
9. View statistics/reports
```

### Test This With:
**Postman:** Run tests in sequence in "Task Assignment Workflow" folder
**cURL:** Run "Quick Test Workflow" section

---

## ⚙️ Environment Variables

### Available in Postman Collection
- `adminToken` - Admin authentication token
- `userToken` - User authentication token
- `requestId` - Current help request ID
- `newRequestId` - Newly created request ID
- `teamId` - Current team ID
- `assignmentId` - Current assignment ID

### Set Up in Postman
1. Click "Environments" (top-right)
2. Create new environment: "Production"
3. Add variables above
4. Select environment before running tests

---

## 🐛 Troubleshooting

### 401 Unauthorized
- Make sure you got a valid token from login
- Check token hasn't expired (if implemented)
- Verify Bearer token syntax: `Bearer YOUR_TOKEN`

### 404 Not Found
- Verify resource ID exists in database
- Run seeders: `php artisan db:seed`
- Check request URL is correct

### 422 Validation Error
- Check all required fields are present
- Verify data types match schema
- Check enum values (status, urgency)

### 500 Internal Server Error
- Check server logs: `tail storage/logs/laravel.log`
- Verify database connection
- Check API server is running

### Token Not Auto-Populating
- Make sure "Admin Login" test ran successfully
- Check test script wasn't disabled
- Verify response has `access_token` field

---

## 📝 Adding New Tests

### In Postman
1. Right-click folder → "Add Request"
2. Set method and URL
3. Add headers if needed
4. Add body for POST/PUT
5. Click "Tests" tab
6. Add test assertions using JavaScript:
   ```javascript
   pm.test('Status code is 200', function () {
       pm.response.to.have.status(200);
   });
   ```

### In cURL
```bash
curl -X METHOD http://localhost:8000/api/endpoint \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"field": "value"}'
```

---

## 🌐 API Base URL

**Development:**
```
http://localhost:8000
```

**Update in Postman:**
1. Click "Environment" dropdown
2. Click "Edit" next to environment name
3. Change `base_url` variable
4. Save

---

## 📚 Related Documentation

- API Documentation: `API_DOCUMENTATION.md`
- Project Summary: `PROJECT_SUMMARY.md`
- Quick Start: `QUICK_START_GUIDE.md`
- Database Schema: `database/` folder

---

## ✅ Validation Checklist

Before deploying to production, ensure all tests pass:

- [ ] Authentication tests pass
- [ ] All CRUD operations work
- [ ] Filtering works correctly
- [ ] Statistics are accurate
- [ ] Error handling works
- [ ] Validation messages are clear
- [ ] Response formats are correct
- [ ] Cross-origin requests work (if needed)
- [ ] Rate limiting works (if implemented)
- [ ] Logging captures all activity

---

## 📞 Support

If tests fail:
1. Check server is running: `php artisan serve`
2. Verify database is seeded: `php artisan db:seed`
3. Check database connection in `.env`
4. Review API error response
5. Check server logs

---

**Last Updated:** 2026-03-12
**API Version:** 1.0
**Postman Version:** Compatible with v10.0+
