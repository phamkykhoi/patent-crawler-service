# Phân tích 2 Folder JPDRP_20241102 và JPDRP_20241106

## Tổng quan

Đây là phân tích chi tiết về 2 folder chứa dữ liệu cập nhật bằng sáng chế Nhật Bản (JPDRP - Japan Patent Data Record Processing) được thu thập vào ngày 02/11/2024 và 06/11/2024.

---

## 1. Số lượng file

### JPDRP_20241102: **10 files**
- `upd_atty_art_p.tsv`
- `upd_atty_art_u.tsv`
- `upd_mgt_info_p.tsv`
- `upd_mgt_info_u.tsv`
- `upd_mrgn_ext_app_num_p.tsv`
- `upd_prog_info_div_p.tsv`
- `upd_prog_info_div_u.tsv`
- `upd_right_person_art_p.tsv`
- `upd_right_person_art_u.tsv`
- `upd_trnsfr_rcpt_info_p.tsv`

### JPDRP_20241106: **11 files**
- Tất cả 10 files trên +
- `upd_trnsfr_rcpt_info_u.tsv` (file mới được thêm)

**Kết luận:** JPDRP_20241106 có nhiều hơn 1 file so với JPDRP_20241102.

---

## 2. Format của từng file

| File Name | Số cột | Format | Ghi chú |
|-----------|--------|--------|---------|
| `upd_atty_art_p.tsv` | 12 | TSV (Tab Separated Values) | Patent |
| `upd_atty_art_u.tsv` | 12 | TSV | Utility Model |
| `upd_mgt_info_p.tsv` | 35 | TSV | Patent |
| `upd_mgt_info_u.tsv` | 32 | TSV | Utility Model |
| `upd_mrgn_ext_app_num_p.tsv` | 8 | TSV | Chỉ có Patent |
| `upd_prog_info_div_p.tsv` | 12 | TSV | Patent |
| `upd_prog_info_div_u.tsv` | 12 | TSV | Utility Model |
| `upd_right_person_art_p.tsv` | 13 | TSV | Patent |
| `upd_right_person_art_u.tsv` | 13 | TSV | Utility Model |
| `upd_trnsfr_rcpt_info_p.tsv` | 8 | TSV | Patent |
| `upd_trnsfr_rcpt_info_u.tsv` | 8 | TSV | Utility Model (chỉ có trong 20241106) |

**Kết luận:** 
- Format giống nhau giữa 2 folder cho các file cùng tên
- Tất cả đều là TSV (Tab Separated Values)
- Các file có cùng tên có cùng số cột

---

## 3. Thông tin của từng file

### 3.1. `upd_atty_art_p.tsv` / `upd_atty_art_u.tsv`
**Nội dung:** Thông tin về luật sư/đại diện sáng chế (Patent Attorney)

**Các cột chính:**
- `processing_type`: Loại xử lý
- `law_cd`: Mã luật (1 = Patent, 2 = Utility Model)
- `reg_num`: Số đăng ký bằng sáng chế
- `split_num`: Số phân chia
- `app_num`: Số đơn
- `rec_num`: Số bản ghi
- `pe_num`: Số PE
- `atty_art_upd_ymd`: Ngày cập nhật thông tin luật sư
- `atty_appl_id`: ID luật sư
- `atty_typ`: Loại luật sư
- `atty_name_len`: Độ dài tên luật sư
- `atty_name`: Tên luật sư

**Phân biệt:**
- `_p`: Dành cho Patent (Sáng chế)
- `_u`: Dành cho Utility Model (Giải pháp hữu ích)

---

### 3.2. `upd_mgt_info_p.tsv` / `upd_mgt_info_u.tsv`
**Nội dung:** Thông tin quản lý bằng sáng chế (Management Information)

**Các cột chính:**
- `processing_type`: Loại xử lý
- `law_cd`: Mã luật
- `reg_num`: Số đăng ký
- `split_num`: Số phân chia
- `mstr_updt_year_month_day`: Ngày cập nhật master
- `tscript_inspct_prhbt_flg`: Cờ cấm kiểm tra bản sao
- `conti_prd_expire_ymd`: Ngày hết hạn giai đoạn tiếp tục
- `next_pen_pymnt_tm_lmt_ymd`: Ngày hạn thanh toán phí tiếp theo
- `last_pymnt_yearly`: Năm thanh toán cuối cùng
- `share_rate`: Tỷ lệ cổ phần
- `app_num`: Số đơn
- `app_year_month_day`: Ngày nộp đơn
- `app_exam_pub_num`: Số công bố kiểm tra đơn
- `app_exam_pub_year_month_day`: Ngày công bố kiểm tra đơn
- `finl_dcsn_year_month_day`: Ngày quyết định cuối cùng
- `trial_dcsn_year_month_day`: Ngày quyết định xét xử
- `set_reg_year_month_day`: Ngày đăng ký thiết lập
- `invent_cnt_claim_cnt_cls_cnt`: Số lượng phát minh, yêu cầu, phân loại
- `invent_title_etc_len`: Độ dài tiêu đề phát minh
- `invent_title_etc`: Tiêu đề phát minh
- `pri_cntry_name_cd`: Mã quốc gia ưu tiên
- `pri_clim_year_month_day`: Ngày yêu cầu ưu tiên
- `pri_clim_cnt`: Số lượng yêu cầu ưu tiên
- `prnt_app_patent_no_prncpl_d_no`: Số đơn/patent cha
- `prnt_p_app_ymd__d_reg_ymd`: Ngày đơn/đăng ký cha
- `prnt_p_app_exam_pub_d_del_ymd`: Ngày công bố/xóa đơn cha

**Phân biệt:**
- `_p`: 35 cột (đầy đủ thông tin)
- `_u`: 32 cột (thiếu một số cột so với `_p`)

---

### 3.3. `upd_mrgn_ext_app_num_p.tsv`
**Nội dung:** Thông tin về ứng dụng ngoại biên (Margin Extended Application Number)

**Các cột:**
- `processing_type`: Loại xử lý
- `law_cd`: Mã luật (chỉ có Patent = 1)
- `reg_num`: Số đăng ký
- `split_num`: Số phân chia
- `app_num`: Số đơn
- `mrgn_info_upd_ymd`: Ngày cập nhật thông tin margin
- `mu_num`: Số MU
- `mrgn_ext_app_num`: Số đơn ứng dụng ngoại biên

**Ghi chú:** Chỉ có file cho Patent (`_p`), không có cho Utility Model (`_u`)

---

### 3.4. `upd_prog_info_div_p.tsv` / `upd_prog_info_div_u.tsv`
**Nội dung:** Thông tin tiến trình/tiến độ (Progress Information Division)

**Các cột:**
- `processing_type`: Loại xử lý
- `law_cd`: Mã luật
- `reg_num`: Số đăng ký
- `split_num`: Số phân chia
- `app_num`: Số đơn
- `rec_num`: Số bản ghi
- `pe_num`: Số PE
- `prog_info_upd_ymd`: Ngày cập nhật thông tin tiến trình
- `reg_intrmd_cd`: Mã đăng ký trung gian
- `crrspnd_mk`: Đánh dấu tương ứng
- `rcpt_pymnt_dsptch_ymd`: Ngày phát hành biên lai thanh toán
- `rcpt_num_common_use`: Số biên lai dùng chung

**Phân biệt:**
- `_p`: Dành cho Patent
- `_u`: Dành cho Utility Model

---

### 3.5. `upd_right_person_art_p.tsv` / `upd_right_person_art_u.tsv`
**Nội dung:** Thông tin về người có quyền (Right Person Article)

**Các cột:**
- `processing_type`: Loại xử lý
- `law_cd`: Mã luật
- `reg_num`: Số đăng ký
- `split_num`: Số phân chia
- `app_num`: Số đơn
- `rec_num`: Số bản ghi
- `pe_num`: Số PE
- `right_psn_art_upd_ymd`: Ngày cập nhật thông tin người có quyền
- `right_person_appl_id`: ID người có quyền
- `right_person_addr_len`: Độ dài địa chỉ người có quyền
- `right_person_addr`: Địa chỉ người có quyền
- `right_person_name_len`: Độ dài tên người có quyền
- `right_person_name`: Tên người có quyền

**Phân biệt:**
- `_p`: Dành cho Patent
- `_u`: Dành cho Utility Model

---

### 3.6. `upd_trnsfr_rcpt_info_p.tsv` / `upd_trnsfr_rcpt_info_u.tsv`
**Nội dung:** Thông tin chuyển nhượng/quyền (Transfer Receipt Information)

**Các cột:**
- `processing_type`: Loại xử lý
- `law_cd`: Mã luật
- `reg_num`: Số đăng ký
- `split_num`: Số phân chia
- `app_num`: Số đơn
- `mrgn_info_upd_ymd`: Ngày cập nhật thông tin margin
- `mu_num`: Số MU
- `trnsfr_rcpt_info`: Thông tin biên lai chuyển nhượng

**Phân biệt:**
- `_p`: Dành cho Patent (có trong cả 2 folder)
- `_u`: Dành cho Utility Model (chỉ có trong JPDRP_20241106)

**Ghi chú:** File `upd_trnsfr_rcpt_info_u.tsv` chỉ xuất hiện trong folder JPDRP_20241106, có thể do dữ liệu Utility Model về chuyển nhượng được cập nhật vào thời điểm này.

---

### 3.7. Chi tiết về `upd_trnsfr_rcpt_info_u.tsv` ⭐

**Đặc điểm đặc biệt:** Đây là file duy nhất chỉ xuất hiện trong JPDRP_20241106 mà không có trong JPDRP_20241102.

#### Thống kê file:
- **Số dòng dữ liệu:** 3 bản ghi (không tính header)
- **Tổng số dòng:** 4 dòng (bao gồm header)
- **Số bằng sáng chế (reg_num) duy nhất:** 3 bằng
- **Loại:** Utility Model (`law_cd = 2`)

#### Dữ liệu mẫu:

| reg_num | app_num | mrgn_info_upd_ymd | trnsfr_rcpt_info |
|---------|---------|-------------------|------------------|
| 3229149 | 2020003489 | 20241105 | 2024000297  20241030移転登録申請 |
| 3236356 | 2021004807 | 20241105 | 2024000298  20241030表示変更登録申請 |
| 3246450 | 2024000519 | 20241011 | 2024000278  20241008表示更正登録申請 |

#### Giải thích các loại thông tin trong `trnsfr_rcpt_info`:

1. **移転登録申請 (Iten Touroku Shinsei)**: Đơn đăng ký chuyển nhượng
   - Ví dụ: `2024000297  20241030移転登録申請`

2. **表示変更登録申請 (Hyouji Henkou Touroku Shinsei)**: Đơn đăng ký thay đổi biểu thị
   - Ví dụ: `2024000298  20241030表示変更登録申請`

3. **表示更正登録申請 (Hyouji Seisei Touroku Shinsei)**: Đơn đăng ký chỉnh sửa biểu thị
   - Ví dụ: `2024000278  20241008表示更正登録申請`

#### So sánh với `upd_trnsfr_rcpt_info_p.tsv`:

| Đặc điểm | `upd_trnsfr_rcpt_info_p.tsv` | `upd_trnsfr_rcpt_info_u.tsv` |
|----------|------------------------------|------------------------------|
| **Số dòng dữ liệu** | ~400 bản ghi | 3 bản ghi |
| **Số bằng sáng chế duy nhất** | ~367 bằng | 3 bằng |
| **Loại** | Patent (`law_cd = 1`) | Utility Model (`law_cd = 2`) |
| **Format** | Giống nhau (8 cột) | Giống nhau (8 cột) |
| **Tần suất cập nhật** | Thường xuyên | Ít hơn (mới xuất hiện) |

#### Lý do file này xuất hiện:

1. **Dữ liệu mới:** Trong khoảng thời gian từ 02/11/2024 đến 06/11/2024, có 3 Utility Model được cập nhật thông tin chuyển nhượng/quyền
2. **Tần suất thấp:** Utility Model có ít giao dịch chuyển nhượng hơn Patent
3. **Cập nhật theo batch:** Dữ liệu được cập nhật theo lô, không phải real-time

---

## 4. Mối quan hệ giữa các file

### 4.1. Khóa liên kết chính
- **`reg_num`**: Số đăng ký bằng sáng chế - là khóa chính để liên kết các file với nhau
- **`app_num`**: Số đơn - cũng được sử dụng để liên kết trong nhiều file
- **`law_cd`**: Mã luật - phân biệt Patent (1) và Utility Model (2)

### 4.2. Cấu trúc dữ liệu

```
┌─────────────────────────────────────┐
│   upd_mgt_info_* (Master Info)      │
│   - Thông tin cơ bản của bằng       │
└──────────────┬──────────────────────┘
               │ reg_num
               │
       ┌───────┴───────┐
       │               │
┌──────▼──────┐  ┌─────▼──────────────┐
│   Patent    │  │  Utility Model     │
│   (_p)      │  │  (_u)             │
└──────┬──────┘  └─────┬─────────────┘
       │               │
       ├───────────────┤
       │               │
┌──────▼───────────────▼──────────────┐
│                                     │
│  upd_atty_art_*        (Luật sư)   │
│  upd_right_person_art_* (Người có quyền) │
│  upd_prog_info_div_*   (Tiến trình) │
│  upd_trnsfr_rcpt_info_* (Chuyển nhượng) │
│                                     │
└─────────────────────────────────────┘
```

### 4.3. Quan hệ giữa các file

1. **`upd_mgt_info_*`**: File chính chứa thông tin cơ bản của bằng sáng chế
   - Liên kết với các file khác qua `reg_num`
   - Có thể có nhiều bản ghi cho cùng một `reg_num` (theo `split_num`)

2. **`upd_atty_art_*`**: Thông tin luật sư/đại diện
   - Một bằng có thể có nhiều luật sư (phân biệt bằng `pe_num`)
   - Liên kết với master qua `reg_num`

3. **`upd_right_person_art_*`**: Thông tin người có quyền
   - Một bằng có thể có nhiều người có quyền (phân biệt bằng `pe_num`)
   - Liên kết với master qua `reg_num`

4. **`upd_prog_info_div_*`**: Thông tin tiến trình
   - Một bằng có thể có nhiều bản ghi tiến trình (phân biệt bằng `pe_num`)
   - Liên kết với master qua `reg_num`

5. **`upd_trnsfr_rcpt_info_*`**: Thông tin chuyển nhượng
   - Một bằng có thể có nhiều bản ghi chuyển nhượng (phân biệt bằng `mu_num`)
   - Liên kết với master qua `reg_num`
   - **Quan hệ đặc biệt:** File này lưu trữ các giao dịch chuyển nhượng quyền sở hữu bằng sáng chế
     - Liên kết với `upd_mgt_info_*` qua `reg_num` để lấy thông tin cơ bản của bằng
     - Liên kết với `upd_right_person_art_*` qua `reg_num` để xem thông tin người có quyền trước và sau chuyển nhượng
     - Có thể có nhiều giao dịch cho cùng một `reg_num` (phân biệt bằng `mu_num`)

6. **`upd_mrgn_ext_app_num_p`**: Chỉ có cho Patent
   - Liên kết với master qua `reg_num`

### 4.5. Mối quan hệ cụ thể của `upd_trnsfr_rcpt_info_u.tsv` với các file khác

#### Ví dụ cụ thể với `reg_num = 3229149`:

**1. Liên kết với `upd_mgt_info_u.tsv`:**
```
reg_num: 3229149
├─ app_num: 2020003489
├─ invent_title_etc: ＬＥＤ照明器具用の光触媒塗料
├─ set_reg_year_month_day: 20201109
└─ conti_prd_expire_ymd: 20300818
```

**2. Liên kết với `upd_trnsfr_rcpt_info_u.tsv`:**
```
reg_num: 3229149
├─ app_num: 2020003489
├─ mrgn_info_upd_ymd: 20241105
├─ mu_num: 000
└─ trnsfr_rcpt_info: 2024000297  20241030移転登録申請
```

**3. Liên kết với `upd_right_person_art_u.tsv`:**
- Có thể tìm thấy thông tin người có quyền hiện tại của bằng này
- Có thể so sánh với thông tin trước khi chuyển nhượng

**4. Liên kết với `upd_prog_info_div_u.tsv`:**
- Có thể xem lịch sử tiến trình của bằng
- Thấy được các cập nhật liên quan đến việc chuyển nhượng

**5. Liên kết với `upd_atty_art_u.tsv`:**
- Có thể xem luật sư đại diện trong quá trình chuyển nhượng

#### Sơ đồ quan hệ cho `upd_trnsfr_rcpt_info_u.tsv`:

```
┌─────────────────────────────────────────┐
│  upd_trnsfr_rcpt_info_u.tsv             │
│  (Transfer Receipt Info - Utility Model) │
│  - 3 bản ghi                             │
│  - reg_num: 3229149, 3236356, 3246450   │
└──────────────┬──────────────────────────┘
               │ reg_num
               │
       ┌───────┴────────┐
       │                │
┌──────▼────────┐  ┌───▼────────────────────────┐
│ upd_mgt_info_u│  │ upd_right_person_art_u     │
│ (Master Info) │  │ (Right Person Info)       │
│               │  │ - Người có quyền hiện tại │
│ - Thông tin   │  │ - Người có quyền trước đó │
│   cơ bản      │  └───────────────────────────┘
└───────┬───────┘
        │
        ├───────────────┬───────────────┐
        │               │               │
┌───────▼──────┐  ┌─────▼──────┐  ┌─────▼──────┐
│upd_atty_art_│  │upd_prog_   │  │Các file    │
│_u           │  │info_div_u  │  │khác        │
│(Luật sư)    │  │(Tiến trình)│  │            │
└─────────────┘  └────────────┘  └────────────┘
```

#### Cách sử dụng dữ liệu:

**Để tra cứu thông tin đầy đủ về một Utility Model có chuyển nhượng:**

1. **Bước 1:** Tìm `reg_num` trong `upd_trnsfr_rcpt_info_u.tsv`
2. **Bước 2:** Sử dụng `reg_num` để tìm trong:
   - `upd_mgt_info_u.tsv` → Thông tin cơ bản của bằng
   - `upd_right_person_art_u.tsv` → Người có quyền hiện tại
   - `upd_atty_art_u.tsv` → Luật sư đại diện
   - `upd_prog_info_div_u.tsv` → Lịch sử tiến trình
3. **Bước 3:** Kết hợp thông tin từ các file để có cái nhìn toàn diện

**Ví dụ truy vấn:**
```sql
-- Tìm tất cả thông tin về Utility Model có chuyển nhượng
SELECT 
    t.reg_num,
    t.trnsfr_rcpt_info,
    m.invent_title_etc,
    m.set_reg_year_month_day,
    r.right_person_name,
    a.atty_name
FROM upd_trnsfr_rcpt_info_u t
JOIN upd_mgt_info_u m ON t.reg_num = m.reg_num
LEFT JOIN upd_right_person_art_u r ON t.reg_num = r.reg_num
LEFT JOIN upd_atty_art_u a ON t.reg_num = a.reg_num
WHERE t.reg_num = '3229149'
```

### 4.4. Quy tắc đặt tên file

- **`_p`**: Patent (Sáng chế) - `law_cd = 1`
- **`_u`**: Utility Model (Giải pháp hữu ích) - `law_cd = 2`
- **`upd_`**: Update (Cập nhật)
- **`art`**: Article (Điều/Điều khoản)
- **`mgt`**: Management (Quản lý)
- **`prog`**: Progress (Tiến trình)
- **`trnsfr`**: Transfer (Chuyển nhượng)
- **`rcpt`**: Receipt (Biên lai)
- **`mrgn`**: Margin (Ngoại biên)
- **`ext`**: Extended (Mở rộng)
- **`app_num`**: Application Number (Số đơn)

---

## 5. So sánh dữ liệu giữa 2 folder

### 5.1. Số lượng dòng dữ liệu

| Folder | Tổng số dòng |
|--------|--------------|
| JPDRP_20241102 | 248,160 dòng |
| JPDRP_20241106 | 292,401 dòng |

**Nhận xét:** JPDRP_20241106 có nhiều dữ liệu hơn (khoảng +44,241 dòng), phù hợp với việc có thêm 1 file và dữ liệu được cập nhật thêm.

### 5.2. Khác biệt chính

1. **File mới:** `upd_trnsfr_rcpt_info_u.tsv` xuất hiện trong JPDRP_20241106
   - Cho thấy có dữ liệu Utility Model về chuyển nhượng được cập nhật vào ngày 06/11/2024

2. **Dữ liệu cập nhật:** Các file trong JPDRP_20241106 có thể chứa dữ liệu mới hơn so với JPDRP_20241102

3. **Format:** Giống nhau hoàn toàn, đảm bảo tính nhất quán

---

## 6. Kết luận

### 6.1. Tổng kết

1. **Số lượng file:** JPDRP_20241106 có nhiều hơn 1 file so với JPDRP_20241102
2. **Format:** Giống nhau hoàn toàn giữa 2 folder, đảm bảo tính nhất quán
3. **Nội dung:** Tất cả các file đều chứa thông tin về bằng sáng chế Nhật Bản, được phân loại theo:
   - Patent (`_p`) và Utility Model (`_u`)
   - Các khía cạnh khác nhau: quản lý, luật sư, người có quyền, tiến trình, chuyển nhượng
4. **Mối quan hệ:** Các file được liên kết với nhau qua `reg_num` (số đăng ký), tạo nên một hệ thống dữ liệu hoàn chỉnh về bằng sáng chế

### 6.2. Khuyến nghị

1. **Sử dụng JPDRP_20241106** làm nguồn dữ liệu chính vì có đầy đủ hơn (có cả file Utility Model về chuyển nhượng)
2. **Liên kết dữ liệu** qua `reg_num` để có cái nhìn toàn diện về một bằng sáng chế
3. **Phân biệt rõ** giữa Patent (`_p`) và Utility Model (`_u`) khi xử lý dữ liệu
4. **Lưu ý** rằng một số file chỉ có cho Patent (như `upd_mrgn_ext_app_num_p.tsv`)

---

## 7. Thông tin kỹ thuật

- **Định dạng:** TSV (Tab Separated Values)
- **Encoding:** UTF-8 (có ký tự tiếng Nhật)
- **Ký tự phân cách:** Tab (`\t`)
- **Dòng đầu tiên:** Header (tên cột)
- **Dữ liệu:** Bắt đầu từ dòng thứ 2

---

**Ngày tạo:** 2024-11-06  
**Người phân tích:** AI Assistant  
**Version:** 1.0

