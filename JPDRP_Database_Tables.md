# Danh sách Table Database cho JPDRP

## Tổng quan

Tài liệu này liệt kê tất cả các table sẽ được tạo từ các file TSV trong folder JPDRP. Mỗi file TSV sẽ tương ứng với một table trong database.

**Tổng số table:** 11 tables

---

## 1. Master Tables (1 record per reg_num)

### 1.1. `upd_mgt_info_p`

**Mô tả:** Thông tin quản lý bằng sáng chế (Patent Management Information)

**File nguồn:** `upd_mgt_info_p.tsv`

**PRIMARY KEY:** `(law_cd, reg_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:**

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (1 = Patent) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `mstr_updt_year_month_day` | VARCHAR(8) | Ngày cập nhật master (YYYYMMDD) |
| `tscript_inspct_prhbt_flg` | VARCHAR(10) | Cờ cấm kiểm tra bản sao |
| `conti_prd_expire_ymd` | VARCHAR(8) | Ngày hết hạn giai đoạn tiếp tục |
| `next_pen_pymnt_tm_lmt_ymd` | VARCHAR(8) | Ngày hạn thanh toán phí tiếp theo |
| `last_pymnt_yearly` | VARCHAR(10) | Năm thanh toán cuối cùng |
| `share_rate` | VARCHAR(50) | Tỷ lệ cổ phần |
| `pblc_prvt_trnsfr_reg_ymd` | VARCHAR(8) | Ngày đăng ký chuyển nhượng công/tư |
| `right_ersr_id` | VARCHAR(50) | ID người có quyền |
| `right_disppr_year_month_day` | VARCHAR(8) | Ngày biến mất quyền |
| `close_orgnl_reg_trnsfr_rec_flg` | VARCHAR(10) | Cờ đóng bản ghi đăng ký gốc |
| `close_reg_year_month_day` | VARCHAR(8) | Ngày đóng đăng ký |
| `gvrnmnt_relation_id_flg` | VARCHAR(10) | Cờ quan hệ chính phủ |
| `pen_suppl_flg` | VARCHAR(10) | Cờ bổ sung phí |
| `trust_reg_flg` | VARCHAR(10) | Cờ đăng ký ủy thác |
| `app_num` | VARCHAR(50) | Số đơn |
| `recvry_num` | VARCHAR(50) | Số phục hồi |
| `app_year_month_day` | VARCHAR(8) | Ngày nộp đơn |
| `app_exam_pub_num` | VARCHAR(50) | Số công bố kiểm tra đơn |
| `app_exam_pub_year_month_day` | VARCHAR(8) | Ngày công bố kiểm tra đơn |
| `finl_dcsn_year_month_day` | VARCHAR(8) | Ngày quyết định cuối cùng |
| `trial_dcsn_year_month_day` | VARCHAR(8) | Ngày quyết định xét xử |
| `set_reg_year_month_day` | VARCHAR(8) | Ngày đăng ký thiết lập |
| `invent_cnt_claim_cnt_cls_cnt` | VARCHAR(50) | Số lượng phát minh, yêu cầu, phân loại |
| `invent_title_etc_len` | VARCHAR(50) | Độ dài tiêu đề phát minh |
| `invent_title_etc` | TEXT | Tiêu đề phát minh |
| `pri_cntry_name_cd` | VARCHAR(10) | Mã quốc gia ưu tiên |
| `pri_clim_year_month_day` | VARCHAR(8) | Ngày yêu cầu ưu tiên |
| `pri_clim_cnt` | VARCHAR(10) | Số lượng yêu cầu ưu tiên |
| `prnt_app_patent_no_prncpl_d_no` | VARCHAR(50) | Số đơn/patent cha |
| `prnt_p_app_ymd__d_reg_ymd` | VARCHAR(8) | Ngày đơn/đăng ký cha |
| `prnt_p_app_exam_pub_d_del_ymd` | VARCHAR(8) | Ngày công bố/xóa đơn cha |

**Tổng số cột:** 35

---

### 1.2. `upd_mgt_info_u`

**Mô tả:** Thông tin quản lý giải pháp hữu ích (Utility Model Management Information)

**File nguồn:** `upd_mgt_info_u.tsv`

**PRIMARY KEY:** `(law_cd, reg_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:**

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (2 = Utility Model) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `mstr_updt_year_month_day` | VARCHAR(8) | Ngày cập nhật master (YYYYMMDD) |
| `tscript_inspct_prhbt_flg` | VARCHAR(10) | Cờ cấm kiểm tra bản sao |
| `conti_prd_expire_ymd` | VARCHAR(8) | Ngày hết hạn giai đoạn tiếp tục |
| `next_pen_pymnt_tm_lmt_ymd` | VARCHAR(8) | Ngày hạn thanh toán phí tiếp theo |
| `last_pymnt_yearly` | VARCHAR(10) | Năm thanh toán cuối cùng |
| `share_rate` | VARCHAR(50) | Tỷ lệ cổ phần |
| `pblc_prvt_trnsfr_reg_ymd` | VARCHAR(8) | Ngày đăng ký chuyển nhượng công/tư |
| `right_ersr_id` | VARCHAR(50) | ID người có quyền |
| `right_disppr_year_month_day` | VARCHAR(8) | Ngày biến mất quyền |
| `close_orgnl_reg_trnsfr_rec_flg` | VARCHAR(10) | Cờ đóng bản ghi đăng ký gốc |
| `close_reg_year_month_day` | VARCHAR(8) | Ngày đóng đăng ký |
| `gvrnmnt_relation_id_flg` | VARCHAR(10) | Cờ quan hệ chính phủ |
| `pen_suppl_flg` | VARCHAR(10) | Cờ bổ sung phí |
| `trust_reg_flg` | VARCHAR(10) | Cờ đăng ký ủy thác |
| `app_num` | VARCHAR(50) | Số đơn |
| `recvry_num` | VARCHAR(50) | Số phục hồi |
| `app_year_month_day` | VARCHAR(8) | Ngày nộp đơn |
| `app_exam_pub_num` | VARCHAR(50) | Số công bố kiểm tra đơn |
| `app_exam_pub_year_month_day` | VARCHAR(8) | Ngày công bố kiểm tra đơn |
| `finl_dcsn_year_month_day` | VARCHAR(8) | Ngày quyết định cuối cùng |
| `trial_dcsn_year_month_day` | VARCHAR(8) | Ngày quyết định xét xử |
| `set_reg_year_month_day` | VARCHAR(8) | Ngày đăng ký thiết lập |
| `invent_cnt_claim_cnt_cls_cnt` | VARCHAR(50) | Số lượng phát minh, yêu cầu, phân loại |
| `invent_title_etc_len` | VARCHAR(50) | Độ dài tiêu đề phát minh |
| `invent_title_etc` | TEXT | Tiêu đề phát minh |
| `pri_cntry_name_cd` | VARCHAR(10) | Mã quốc gia ưu tiên |
| `pri_clim_year_month_day` | VARCHAR(8) | Ngày yêu cầu ưu tiên |
| `pri_clim_cnt` | VARCHAR(10) | Số lượng yêu cầu ưu tiên |

**Tổng số cột:** 32

**Ghi chú:** Thiếu 3 cột cuối so với `upd_mgt_info_p` (các cột về parent patent)

---

### 1.3. `upd_mrgn_ext_app_num_p`

**Mô tả:** Thông tin ứng dụng ngoại biên (Margin Extended Application Number) - Chỉ có cho Patent

**File nguồn:** `upd_mrgn_ext_app_num_p.tsv`

**PRIMARY KEY:** `(law_cd, reg_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:**

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (1 = Patent) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `mrgn_info_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin margin |
| `mu_num` | VARCHAR(10) | Số MU |
| `mrgn_ext_app_num` | VARCHAR(50) | Số đơn ứng dụng ngoại biên |

**Tổng số cột:** 8

**Ghi chú:** Chỉ có file cho Patent, không có cho Utility Model

---

## 2. Detail Tables với pe_num (nhiều records per reg_num)

### 2.1. `upd_atty_art_p`

**Mô tả:** Thông tin luật sư/đại diện sáng chế (Patent Attorney) - Patent

**File nguồn:** `upd_atty_art_p.tsv`

**PRIMARY KEY:** `(law_cd, reg_num, pe_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:**

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (1 = Patent) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `rec_num` | VARCHAR(10) | Số bản ghi |
| `pe_num` | VARCHAR(10) | Số PE (phân biệt các luật sư khác nhau) |
| `atty_art_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin luật sư |
| `atty_appl_id` | VARCHAR(50) | ID luật sư |
| `atty_typ` | INT | Loại luật sư |
| `atty_name_len` | VARCHAR(50) | Độ dài tên luật sư |
| `atty_name` | VARCHAR(255) | Tên luật sư |

**Tổng số cột:** 12

**Ghi chú:** Một bằng có thể có nhiều luật sư (phân biệt bằng `pe_num`)

---

### 2.2. `upd_atty_art_u`

**Mô tả:** Thông tin luật sư/đại diện sáng chế (Patent Attorney) - Utility Model

**File nguồn:** `upd_atty_art_u.tsv`

**PRIMARY KEY:** `(law_cd, reg_num, pe_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:** (Giống như `upd_atty_art_p`)

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (2 = Utility Model) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `rec_num` | VARCHAR(10) | Số bản ghi |
| `pe_num` | VARCHAR(10) | Số PE (phân biệt các luật sư khác nhau) |
| `atty_art_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin luật sư |
| `atty_appl_id` | VARCHAR(50) | ID luật sư |
| `atty_typ` | INT | Loại luật sư |
| `atty_name_len` | VARCHAR(50) | Độ dài tên luật sư |
| `atty_name` | VARCHAR(255) | Tên luật sư |

**Tổng số cột:** 12

---

### 2.3. `upd_prog_info_div_p`

**Mô tả:** Thông tin tiến trình/tiến độ (Progress Information Division) - Patent

**File nguồn:** `upd_prog_info_div_p.tsv`

**PRIMARY KEY:** `(law_cd, reg_num, pe_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:**

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (1 = Patent) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `rec_num` | VARCHAR(10) | Số bản ghi |
| `pe_num` | VARCHAR(10) | Số PE (phân biệt các bản ghi tiến trình) |
| `prog_info_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin tiến trình |
| `reg_intrmd_cd` | VARCHAR(50) | Mã đăng ký trung gian |
| `crrspnd_mk` | VARCHAR(10) | Đánh dấu tương ứng |
| `rcpt_pymnt_dsptch_ymd` | VARCHAR(8) | Ngày phát hành biên lai thanh toán |
| `rcpt_num_common_use` | VARCHAR(50) | Số biên lai dùng chung |

**Tổng số cột:** 12

**Ghi chú:** Một bằng có thể có nhiều bản ghi tiến trình theo thời gian (phân biệt bằng `pe_num`)

---

### 2.4. `upd_prog_info_div_u`

**Mô tả:** Thông tin tiến trình/tiến độ (Progress Information Division) - Utility Model

**File nguồn:** `upd_prog_info_div_u.tsv`

**PRIMARY KEY:** `(law_cd, reg_num, pe_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:** (Giống như `upd_prog_info_div_p`)

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (2 = Utility Model) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `rec_num` | VARCHAR(10) | Số bản ghi |
| `pe_num` | VARCHAR(10) | Số PE (phân biệt các bản ghi tiến trình) |
| `prog_info_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin tiến trình |
| `reg_intrmd_cd` | VARCHAR(50) | Mã đăng ký trung gian |
| `crrspnd_mk` | VARCHAR(10) | Đánh dấu tương ứng |
| `rcpt_pymnt_dsptch_ymd` | VARCHAR(8) | Ngày phát hành biên lai thanh toán |
| `rcpt_num_common_use` | VARCHAR(50) | Số biên lai dùng chung |

**Tổng số cột:** 12

---

### 2.5. `upd_right_person_art_p`

**Mô tả:** Thông tin người có quyền (Right Person Article) - Patent

**File nguồn:** `upd_right_person_art_p.tsv`

**PRIMARY KEY:** `(law_cd, reg_num, pe_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:**

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (1 = Patent) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `rec_num` | VARCHAR(10) | Số bản ghi |
| `pe_num` | VARCHAR(10) | Số PE (phân biệt các người có quyền) |
| `right_psn_art_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin người có quyền |
| `right_person_appl_id` | VARCHAR(50) | ID người có quyền |
| `right_person_addr_len` | VARCHAR(50) | Độ dài địa chỉ người có quyền |
| `right_person_addr` | VARCHAR(255) | Địa chỉ người có quyền |
| `right_person_name_len` | VARCHAR(50) | Độ dài tên người có quyền |
| `right_person_name` | VARCHAR(255) | Tên người có quyền |

**Tổng số cột:** 13

**Ghi chú:** Một bằng có thể có nhiều người có quyền (phân biệt bằng `pe_num`)

---

### 2.6. `upd_right_person_art_u`

**Mô tả:** Thông tin người có quyền (Right Person Article) - Utility Model

**File nguồn:** `upd_right_person_art_u.tsv`

**PRIMARY KEY:** `(law_cd, reg_num, pe_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:** (Giống như `upd_right_person_art_p`)

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (2 = Utility Model) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `rec_num` | VARCHAR(10) | Số bản ghi |
| `pe_num` | VARCHAR(10) | Số PE (phân biệt các người có quyền) |
| `right_psn_art_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin người có quyền |
| `right_person_appl_id` | VARCHAR(50) | ID người có quyền |
| `right_person_addr_len` | VARCHAR(50) | Độ dài địa chỉ người có quyền |
| `right_person_addr` | VARCHAR(255) | Địa chỉ người có quyền |
| `right_person_name_len` | VARCHAR(50) | Độ dài tên người có quyền |
| `right_person_name` | VARCHAR(255) | Tên người có quyền |

**Tổng số cột:** 13

---

## 3. Detail Tables với mu_num (nhiều records per reg_num)

### 3.1. `upd_trnsfr_rcpt_info_p`

**Mô tả:** Thông tin chuyển nhượng/quyền (Transfer Receipt Information) - Patent

**File nguồn:** `upd_trnsfr_rcpt_info_p.tsv`

**PRIMARY KEY:** `(law_cd, reg_num, mu_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:**

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (1 = Patent) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `mrgn_info_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin margin |
| `mu_num` | VARCHAR(10) | Số MU (phân biệt các giao dịch chuyển nhượng) |
| `trnsfr_rcpt_info` | TEXT | Thông tin biên lai chuyển nhượng |

**Tổng số cột:** 8

**Ghi chú:** 
- Một bằng có thể có nhiều giao dịch chuyển nhượng (phân biệt bằng `mu_num`)
- File này có trong cả 2 folder (JPDRP_20241102 và JPDRP_20241106)

---

### 3.2. `upd_trnsfr_rcpt_info_u`

**Mô tả:** Thông tin chuyển nhượng/quyền (Transfer Receipt Information) - Utility Model

**File nguồn:** `upd_trnsfr_rcpt_info_u.tsv`

**PRIMARY KEY:** `(law_cd, reg_num, mu_num)`

**Index:** `idx_reg_num (reg_num)`

**Các cột:** (Giống như `upd_trnsfr_rcpt_info_p`)

| Tên cột | Kiểu dữ liệu | Mô tả |
|---------|--------------|-------|
| `processing_type` | INT | Loại xử lý |
| `law_cd` | INT | Mã luật (2 = Utility Model) |
| `reg_num` | VARCHAR(50) | Số đăng ký bằng sáng chế |
| `split_num` | VARCHAR(50) | Số phân chia |
| `app_num` | VARCHAR(50) | Số đơn |
| `mrgn_info_upd_ymd` | VARCHAR(8) | Ngày cập nhật thông tin margin |
| `mu_num` | VARCHAR(10) | Số MU (phân biệt các giao dịch chuyển nhượng) |
| `trnsfr_rcpt_info` | TEXT | Thông tin biên lai chuyển nhượng |

**Tổng số cột:** 8

**Ghi chú:** 
- Chỉ có trong folder JPDRP_20241106 (không có trong JPDRP_20241102)
- Có 3 bản ghi dữ liệu trong file đầu tiên

---

## 4. Tóm tắt

### 4.1. Phân loại theo loại khóa

| Loại | Số lượng | Tables |
|------|----------|--------|
| **Master (law_cd, reg_num)** | 3 | `upd_mgt_info_p`, `upd_mgt_info_u`, `upd_mrgn_ext_app_num_p` |
| **Detail với pe_num (law_cd, reg_num, pe_num)** | 6 | `upd_atty_art_p`, `upd_atty_art_u`, `upd_prog_info_div_p`, `upd_prog_info_div_u`, `upd_right_person_art_p`, `upd_right_person_art_u` |
| **Detail với mu_num (law_cd, reg_num, mu_num)** | 2 | `upd_trnsfr_rcpt_info_p`, `upd_trnsfr_rcpt_info_u` |
| **Tổng** | **11** | |

### 4.2. Phân loại theo Patent/Utility Model

| Loại | Số lượng | Tables |
|------|----------|--------|
| **Patent (_p)** | 6 | `upd_atty_art_p`, `upd_mgt_info_p`, `upd_mrgn_ext_app_num_p`, `upd_prog_info_div_p`, `upd_right_person_art_p`, `upd_trnsfr_rcpt_info_p` |
| **Utility Model (_u)** | 5 | `upd_atty_art_u`, `upd_mgt_info_u`, `upd_prog_info_div_u`, `upd_right_person_art_u`, `upd_trnsfr_rcpt_info_u` |

### 4.3. Quan hệ giữa các tables

```
┌─────────────────────────────────────┐
│   upd_mgt_info_* (Master)          │
│   PRIMARY KEY: (law_cd, reg_num)    │
└──────────────┬──────────────────────┘
               │ reg_num (Foreign Key)
               │
       ┌───────┴───────────────┐
       │                       │
┌──────▼──────────┐   ┌────────▼─────────────┐
│ Detail Tables   │   │ Detail Tables        │
│ với pe_num      │   │ với mu_num           │
│                 │   │                      │
│ - atty_art_*    │   │ - trnsfr_rcpt_info_* │
│ - prog_info_*   │   │                      │
│ - right_person_*│   │                      │
│                 │   │                      │
│ PRIMARY KEY:    │   │ PRIMARY KEY:         │
│ (law_cd,        │   │ (law_cd,             │
│  reg_num,       │   │  reg_num,            │
│  pe_num)        │   │  mu_num)             │
└─────────────────┘   └─────────────────────┘
```

### 4.4. Quy tắc INSERT/UPDATE

| Loại table | Folder đầu tiên | Folder tiếp theo |
|------------|----------------|------------------|
| **Master** | INSERT tất cả | UPSERT: Nếu `(law_cd, reg_num)` tồn tại → UPDATE, nếu không → INSERT |
| **Detail với pe_num** | INSERT tất cả | UPSERT: Nếu `(law_cd, reg_num, pe_num)` tồn tại → UPDATE, nếu không → INSERT |
| **Detail với mu_num** | INSERT tất cả | UPSERT: Nếu `(law_cd, reg_num, mu_num)` tồn tại → UPDATE, nếu không → INSERT |

---

## 5. Lưu ý khi tạo schema

### 5.1. Kiểu dữ liệu đề xuất

- **INT**: Cho `processing_type`, `law_cd`, `atty_typ`
- **VARCHAR(50)**: Cho các trường ID và mã ngắn
- **VARCHAR(10)**: Cho các trường mã ngắn như `pe_num`, `mu_num`, `rec_num`
- **VARCHAR(8)**: Cho các trường ngày tháng (YYYYMMDD)
- **VARCHAR(255)**: Cho các trường tên và địa chỉ
- **TEXT**: Cho các trường có thể dài như `invent_title_etc`, `trnsfr_rcpt_info`

### 5.2. Index đề xuất

- **PRIMARY KEY**: Trên các khóa đã xác định
- **INDEX**: Trên `reg_num` để tăng tốc JOIN và tìm kiếm
- **Có thể thêm**: INDEX trên `app_num` nếu cần tìm kiếm theo số đơn

### 5.3. Character Set

- Sử dụng `utf8mb4` để hỗ trợ đầy đủ ký tự tiếng Nhật và các ký tự đặc biệt
- Collation: `utf8mb4_unicode_ci`

---

**Ngày tạo:** 2024-11-06  
**Version:** 1.0

