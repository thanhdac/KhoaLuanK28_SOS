#!/bin/bash

# Rescue Management API - cURL Test Commands
# This script contains all curl commands for testing the API

# Color codes
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}Rescue Management API - cURL Test Guide${NC}"
echo -e "${BLUE}========================================${NC}\n"

# ============================================
# 1. AUTHENTICATION
# ============================================
echo -e "${YELLOW}1. AUTHENTICATION${NC}\n"

echo -e "${GREEN}1.1 Admin Login${NC}"
echo 'Command:'
echo 'curl -X POST http://localhost:8000/api/admin/login \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "{\"email\": \"admin@example.com\", \"password\": \"admin123\"}"'
echo ""

echo -e "${GREEN}1.2 User Login${NC}"
echo 'Command:'
echo 'curl -X POST http://localhost:8000/api/nguoi-dung/login \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "{\"email\": \"huong1@example.com\", \"password\": \"user123\"}"'
echo ""

# ============================================
# 2. HELP REQUESTS - CRUD
# ============================================
echo -e "${YELLOW}2. HELP REQUESTS (YEU CAU CUU HO)${NC}\n"

echo -e "${GREEN}2.1 Get All Help Requests${NC}"
echo 'Command:'
echo 'curl -X GET http://localhost:8000/api/yeu-cau-cuu-ho \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

echo -e "${GREEN}2.2 Get Help Request by ID (ID=1)${NC}"
echo 'Command:'
echo 'curl -X GET http://localhost:8000/api/yeu-cau-cuu-ho/1 \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

echo -e "${GREEN}2.3 Create New Help Request${NC}"
echo 'Command:'
echo 'curl -X POST http://localhost:8000/api/yeu-cau-cuu-ho \'
echo '  -H "Authorization: Bearer YOUR_USER_TOKEN" \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "'\''{
echo '    "id_nguoi_dung": 1,
echo '    "id_loai_su_co": 1,
echo '    "vi_tri_lat": 10.7769,
echo '    "vi_tri_lng": 106.7009,
echo '    "vi_tri_dia_chi": "Tòa nhà 128 Lê Lợi, Q1",
echo '    "chi_tiet": "Cháy tầng 4",
echo '    "mo_ta": "Cần cứu hộ ngay",
echo '    "hinh_anh": "fire.jpg",
echo '    "so_nguoi_bi_anh_huong": 8,
echo '    "muc_do_khan_cap": "CRITICAL",
echo '    "diem_uu_tien": 10
echo '  }'\''"'
echo ""

echo -e "${GREEN}2.4 Update Help Request Status${NC}"
echo 'Command:'
echo 'curl -X PUT http://localhost:8000/api/yeu-cau-cuu-ho/1 \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "{\"trang_thai\": \"DANG_XU_LY\"}"'
echo ""

echo -e "${GREEN}2.5 Filter by Status (CHO_XU_LY)${NC}"
echo 'Command:'
echo 'curl -X GET "http://localhost:8000/api/yeu-cau-cuu-ho/theo-trang-thai/CHO_XU_LY" \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

echo -e "${GREEN}2.6 Filter by Urgency (CRITICAL)${NC}"
echo 'Command:'
echo 'curl -X GET "http://localhost:8000/api/yeu-cau-cuu-ho/theo-do-khan-cap/CRITICAL" \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

echo -e "${GREEN}2.7 Search Help Requests${NC}"
echo 'Command:'
echo 'curl -X GET "http://localhost:8000/api/tim-kiem/yeu-cau?keyword=chay&status=DANG_XU_LY" \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

# ============================================
# 3. RESCUE TEAMS
# ============================================
echo -e "${YELLOW}3. RESCUE TEAMS (DOI CUU HO)${NC}\n"

echo -e "${GREEN}3.1 Get All Teams${NC}"
echo 'Command:'
echo 'curl -X GET http://localhost:8000/api/doi-cuu-ho \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

echo -e "${GREEN}3.2 Get Team Details (ID=1)${NC}"
echo 'Command:'
echo 'curl -X GET http://localhost:8000/api/doi-cuu-ho/1 \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

# ============================================
# 4. TASK ASSIGNMENT WORKFLOW
# ============================================
echo -e "${YELLOW}4. TASK ASSIGNMENT (PHAN CONG CUU HO)${NC}\n"

echo -e "${GREEN}4.1 Create Task Assignment${NC}"
echo 'Command:'
echo 'curl -X POST http://localhost:8000/api/phan-cong-cuu-ho \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "'\''{
echo '    "id_yeu_cau": 1,
echo '    "id_doi_cuu_ho": 1,
echo '    "mo_ta": "Xử lý cháy tòa nhà Q1",
echo '    "thoi_gian_phan_cong": "2026-03-12T10:00:00Z",
echo '    "trang_thai_nhiem_vu": "DANG_XU_LY"
echo '  }'\''"'
echo ""

echo -e "${GREEN}4.2 Update Assignment Status${NC}"
echo 'Command:'
echo 'curl -X PUT http://localhost:8000/api/phan-cong-cuu-ho/1 \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "{\"trang_thai_nhiem_vu\": \"HOAN_THANH\"}"'
echo ""

# ============================================
# 5. RESCUE RESULTS
# ============================================
echo -e "${YELLOW}5. RESCUE RESULTS (KET QUA CUU HO)${NC}\n"

echo -e "${GREEN}5.1 Create Rescue Result${NC}"
echo 'Command:'
echo 'curl -X POST http://localhost:8000/api/ket-qua-cuu-ho \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "'\''{
echo '    "id_phan_cong": 1,
echo '    "bao_cao_hien_truong": "Đã kiểm soát tình hình. Sơ tán an toàn",
echo '    "trang_thai_ket_qua": "HOAN_THANH",
echo '    "hinh_anh_minh_chung": "result.jpg",
echo '    "thoi_gian_ket_thuc": "2026-03-12T10:45:00Z"
echo '  }'\''"'
echo ""

# ============================================
# 6. RATINGS
# ============================================
echo -e "${YELLOW}6. RATINGS (DANH GIA CUU HO)${NC}\n"

echo -e "${GREEN}6.1 Create Rating${NC}"
echo 'Command:'
echo 'curl -X POST http://localhost:8000/api/danh-gia-cuu-ho \'
echo '  -H "Authorization: Bearer YOUR_USER_TOKEN" \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "'\''{
echo '    "id_yeu_cau": 1,
echo '    "id_nguoi_dung": 5,
echo '    "diem_danh_gia": 5,
echo '    "noi_dung_danh_gia": "Xử lý tốt, chuyên nghiệp!"
echo '  }'\''"'
echo ""

# ============================================
# 7. STATISTICS
# ============================================
echo -e "${YELLOW}7. STATISTICS (THONG KE)${NC}\n"

echo -e "${GREEN}7.1 Total Help Requests${NC}"
echo 'Command:'
echo 'curl -X GET http://localhost:8000/api/thong-ke/tong-so-yeu-cau \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

echo -e "${GREEN}7.2 Requests by Status${NC}"
echo 'Command:'
echo 'curl -X GET http://localhost:8000/api/thong-ke/yeu-cau-theo-trang-thai \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

echo -e "${GREEN}7.3 Requests by Urgency${NC}"
echo 'Command:'
echo 'curl -X GET http://localhost:8000/api/thong-ke/yeu-cau-theo-do-khan-cap \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

echo -e "${GREEN}7.4 Processing Queue Status${NC}"
echo 'Command:'
echo 'curl -X GET http://localhost:8000/api/thong-ke/trang-thai-xu-ly \'
echo '  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"'
echo ""

# ============================================
# 8. QUICK TEST WORKFLOW
# ============================================
echo -e "${YELLOW}8. QUICK TEST WORKFLOW${NC}\n"
echo "Run these commands in sequence to test the complete workflow:\n"

echo -e "${GREEN}Step 1: Get Admin Token${NC}"
echo 'TOKEN=$(curl -s -X POST http://localhost:8000/api/admin/login \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "{\"email\": \"admin@example.com\", \"password\": \"admin123\"}" | jq -r ".access_token")'
echo 'echo "Admin Token: $TOKEN"'
echo ""

echo -e "${GREEN}Step 2: Get First Help Request${NC}"
echo 'curl -s -X GET http://localhost:8000/api/yeu-cau-cuu-ho \'
echo '  -H "Authorization: Bearer $TOKEN" | jq ".[0]"'
echo ""

echo -e "${GREEN}Step 3: Get First Team${NC}"
echo 'curl -s -X GET http://localhost:8000/api/doi-cuu-ho \'
echo '  -H "Authorization: Bearer $TOKEN" | jq ".[0]"'
echo ""

echo -e "${GREEN}Step 4: Create Assignment${NC}"
echo 'curl -s -X POST http://localhost:8000/api/phan-cong-cuu-ho \'
echo '  -H "Authorization: Bearer $TOKEN" \'
echo '  -H "Content-Type: application/json" \'
echo '  -d "{\"id_yeu_cau\": 1, \"id_doi_cuu_ho\": 1, \"mo_ta\": \"Test assignment\", \"trang_thai_nhiem_vu\": \"DANG_XU_LY\"}" | jq "."'
echo ""

echo -e "${GREEN}Step 5: Get Statistics${NC}"
echo 'curl -s -X GET http://localhost:8000/api/thong-ke/tong-so-yeu-cau \'
echo '  -H "Authorization: Bearer $TOKEN" | jq "."'
echo ""

# ============================================
# 9. SAMPLE TEST DATA
# ============================================
echo -e "${YELLOW}9. SAMPLE TEST DATA${NC}\n"

echo "Admin Account:"
echo "  Email: admin@example.com"
echo "  Password: admin123"
echo ""

echo "User Accounts:"
echo "  Email: huong1@example.com | Password: user123"
echo "  Email: hung1@example.com  | Password: user123"
echo "  Email: hoa1@example.com   | Password: user123"
echo ""

echo "Incident Types:"
echo "  1 = Cháy (Fire)"
echo "  2 = Đuối nước (Drowning)"
echo "  3 = Tai nạn giao thông (Traffic)"
echo "  4 = Sập nhà (Collapse)"
echo ""

echo "Teams:"
echo "  1 = Đội Cứu Hộ Q1"
echo "  2 = Đội Cứu Hộ Q3"
echo "  3 = Đội Cứu Hộ Q5"
echo ""

echo "Status Values:"
echo "  CHO_XU_LY   = Waiting"
echo "  DANG_XU_LY  = Processing"
echo "  HOAN_THANH  = Completed"
echo "  HUY_BO      = Cancelled"
echo ""

echo "Urgency Levels:"
echo "  LOW      = Urgent"
echo "  MEDIUM   = Medium"
echo "  HIGH     = High"
echo "  CRITICAL = Critical"
echo ""

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}End of cURL Test Guide${NC}"
echo -e "${BLUE}========================================${NC}\n"
