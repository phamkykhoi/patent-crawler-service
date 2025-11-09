# Phân tích và Hướng giải quyết cho việc Đồng bộ dữ liệu JPDRP vào Database

## 1. Phân tích cấu trúc dữ liệu và khóa duy nhất

### 1.1. Khóa duy nhất cho từng loại file

Dựa trên phân tích các file TSV, mỗi file có cấu trúc khóa duy nhất như sau:

#### A. File có 1 record/reg_num (Master Files):
- **`upd_mgt_info_p.tsv`** / **`upd_mgt_info_u.tsv`**
  - **Khóa duy nhất:** `(law_cd, reg_num)`
  - **Lý do:** Mỗi bằng sáng chế chỉ có 1 bản ghi quản lý chính
  
- **`upd_mrgn_ext_app_num_p.tsv`**
  - **Khóa duy nhất:** `(law_cd, reg_num)`
  - **Lý do:** Mỗi bằng chỉ có 1 bản ghi ứng dụng ngoại biên
  - **Ghi chú:** Chỉ có cho Patent

#### B. File có nhiều records/reg_num (Detail Files):

- **`upd_atty_art_p.tsv`** / **`upd_atty_art_u.tsv`**
  - **Khóa duy nhất:** `(law_cd, reg_num, pe_num)`
  - **Lý do:** Một bằng có thể có nhiều luật sư, phân biệt bằng `pe_num`
  - **Ví dụ:** reg_num=5120945 có 7 luật sư (pe_num từ 000 đến 006)

- **`upd_prog_info_div_p.tsv`** / **`upd_prog_info_div_u.tsv`**
  - **Khóa duy nhất:** `(law_cd, reg_num, pe_num)`
  - **Lý do:** Một bằng có nhiều bản ghi tiến trình theo thời gian, phân biệt bằng `pe_num`
  - **Ví dụ:** reg_num=4867878 có nhiều bản ghi tiến trình (009, 010, 011, ...)

- **`upd_right_person_art_p.tsv`** / **`upd_right_person_art_u.tsv`**
  - **Khóa duy nhất:** `(law_cd, reg_num, pe_num)`
  - **Lý do:** Một bằng có thể có nhiều người có quyền, phân biệt bằng `pe_num`
  - **Ví dụ:** reg_num=3788480 có 2 người có quyền (pe_num: 000, 001)

- **`upd_trnsfr_rcpt_info_p.tsv`** / **`upd_trnsfr_rcpt_info_u.tsv`**
  - **Khóa duy nhất:** `(law_cd, reg_num, mu_num)`
  - **Lý do:** Một bằng có thể có nhiều giao dịch chuyển nhượng, phân biệt bằng `mu_num`
  - **Ví dụ:** reg_num=4679913 có 2 giao dịch (mu_num: 000, 001)

### 1.2. Tại sao cần `law_cd` trong khóa?

- `law_cd = 1`: Patent (Sáng chế)
- `law_cd = 2`: Utility Model (Giải pháp hữu ích)
- Có thể có cùng `reg_num` giữa Patent và Utility Model (ví dụ: 3229149 có thể tồn tại ở cả 2)
- Đảm bảo tính duy nhất tuyệt đối

---

## 2. Kịch bản UPDATE/INSERT

### 2.1. Kịch bản 1: Master Files (1 record/reg_num)

**File:** `upd_mgt_info_*`, `upd_mrgn_ext_app_num_p`

**Logic:**
- Folder đầu tiên: INSERT tất cả
- Folder tiếp theo:
  - Nếu `(law_cd, reg_num)` đã tồn tại → **UPDATE** tất cả các cột
  - Nếu chưa tồn tại → **INSERT**

**Lý do:** Master file lưu thông tin hiện tại của bằng, nên cập nhật khi có thay đổi

### 2.2. Kịch bản 2: Detail Files với pe_num

**File:** `upd_atty_art_*`, `upd_prog_info_div_*`, `upd_right_person_art_*`

**Logic:**
- Folder đầu tiên: INSERT tất cả
- Folder tiếp theo:
  - Nếu `(law_cd, reg_num, pe_num)` đã tồn tại → **UPDATE** tất cả các cột
  - Nếu chưa tồn tại → **INSERT**

**Lý do:** 
- Có thể có luật sư/người có quyền mới được thêm (`pe_num` mới)
- Có thể có thông tin được cập nhật cho cùng một `pe_num`

**Ví dụ:**
- Folder 1: reg_num=5120945 có luật sư pe_num=000
- Folder 2: reg_num=5120945 có luật sư pe_num=000 (cập nhật) + pe_num=007 (mới)
  → UPDATE pe_num=000, INSERT pe_num=007

### 2.3. Kịch bản 3: Detail Files với mu_num

**File:** `upd_trnsfr_rcpt_info_*`

**Logic:**
- Folder đầu tiên: INSERT tất cả
- Folder tiếp theo:
  - Nếu `(law_cd, reg_num, mu_num)` đã tồn tại → **UPDATE** tất cả các cột
  - Nếu chưa tồn tại → **INSERT**

**Lý do:** 
- Có thể có giao dịch chuyển nhượng mới (`mu_num` mới)
- Có thể có thông tin được cập nhật cho cùng một `mu_num`

**Ví dụ:**
- Folder 1: reg_num=4679913 không có trong file
- Folder 2: reg_num=4679913 có 2 giao dịch (mu_num: 000, 001)
  → INSERT cả 2

---

## 3. Chiến lược Implementation

### 3.1. Phương án 1: UPSERT (ON DUPLICATE KEY UPDATE) - Khuyến nghị

**Ưu điểm:**
- Xử lý INSERT/UPDATE trong 1 câu lệnh SQL
- Hiệu suất cao, atomic operation
- Đơn giản, dễ maintain

**Cách thực hiện:**

```sql
-- Ví dụ cho upd_mgt_info_p
INSERT INTO upd_mgt_info_p (
    processing_type, law_cd, reg_num, split_num, 
    mstr_updt_year_month_day, ...
) VALUES (?, ?, ?, ?, ?, ...)
ON DUPLICATE KEY UPDATE
    split_num = VALUES(split_num),
    mstr_updt_year_month_day = VALUES(mstr_updt_year_month_day),
    ...
```

**Yêu cầu:**
- Tạo PRIMARY KEY hoặc UNIQUE KEY trên `(law_cd, reg_num)`
- Hoặc `(law_cd, reg_num, pe_num)` cho detail files
- Hoặc `(law_cd, reg_num, mu_num)` cho transfer files

### 3.2. Phương án 2: INSERT ... ON CONFLICT (PostgreSQL)

**Ưu điểm:**
- PostgreSQL native support
- Linh hoạt hơn với conflict resolution

**Cách thực hiện:**

```sql
-- Ví dụ cho upd_mgt_info_p
INSERT INTO upd_mgt_info_p (
    processing_type, law_cd, reg_num, split_num, ...
) VALUES (?, ?, ?, ?, ...)
ON CONFLICT (law_cd, reg_num) 
DO UPDATE SET
    split_num = EXCLUDED.split_num,
    mstr_updt_year_month_day = EXCLUDED.mstr_updt_year_month_day,
    ...
```

### 3.3. Phương án 3: SELECT → INSERT hoặc UPDATE

**Ưu điểm:**
- Hoạt động với mọi database
- Có thể có logic phức tạp hơn

**Nhược điểm:**
- Cần 2 câu lệnh SQL (SELECT + INSERT/UPDATE)
- Kém hiệu quả hơn với dữ liệu lớn

**Cách thực hiện:**

```python
# Pseudocode
for row in csv_rows:
    exists = db.query("SELECT 1 FROM table WHERE key = ?", key)
    if exists:
        db.update("UPDATE table SET ... WHERE key = ?", key)
    else:
        db.insert("INSERT INTO table VALUES ...")
```

---

## 4. Cấu trúc Database Schema

### 4.1. Bảng Master Files

```sql
-- upd_mgt_info_p
CREATE TABLE upd_mgt_info_p (
    processing_type INT,
    law_cd INT,
    reg_num VARCHAR(50),
    split_num VARCHAR(50),
    mstr_updt_year_month_day VARCHAR(8),
    -- ... các cột khác
    PRIMARY KEY (law_cd, reg_num),
    INDEX idx_reg_num (reg_num)
);

-- upd_mgt_info_u
CREATE TABLE upd_mgt_info_u (
    -- tương tự như trên
    PRIMARY KEY (law_cd, reg_num)
);

-- upd_mrgn_ext_app_num_p
CREATE TABLE upd_mrgn_ext_app_num_p (
    processing_type INT,
    law_cd INT,
    reg_num VARCHAR(50),
    split_num VARCHAR(50),
    app_num VARCHAR(50),
    mrgn_info_upd_ymd VARCHAR(8),
    mu_num VARCHAR(10),
    mrgn_ext_app_num VARCHAR(50),
    PRIMARY KEY (law_cd, reg_num)
);
```

### 4.2. Bảng Detail Files với pe_num

```sql
-- upd_atty_art_p
CREATE TABLE upd_atty_art_p (
    processing_type INT,
    law_cd INT,
    reg_num VARCHAR(50),
    split_num VARCHAR(50),
    app_num VARCHAR(50),
    rec_num VARCHAR(10),
    pe_num VARCHAR(10),
    atty_art_upd_ymd VARCHAR(8),
    atty_appl_id VARCHAR(50),
    atty_typ INT,
    atty_name_len INT,
    atty_name VARCHAR(255),
    PRIMARY KEY (law_cd, reg_num, pe_num),
    INDEX idx_reg_num (reg_num)
);

-- upd_prog_info_div_p
CREATE TABLE upd_prog_info_div_p (
    processing_type INT,
    law_cd INT,
    reg_num VARCHAR(50),
    split_num VARCHAR(50),
    app_num VARCHAR(50),
    rec_num VARCHAR(10),
    pe_num VARCHAR(10),
    prog_info_upd_ymd VARCHAR(8),
    reg_intrmd_cd VARCHAR(50),
    crrspnd_mk VARCHAR(10),
    rcpt_pymnt_dsptch_ymd VARCHAR(8),
    rcpt_num_common_use VARCHAR(50),
    PRIMARY KEY (law_cd, reg_num, pe_num),
    INDEX idx_reg_num (reg_num)
);

-- upd_right_person_art_p
CREATE TABLE upd_right_person_art_p (
    processing_type INT,
    law_cd INT,
    reg_num VARCHAR(50),
    split_num VARCHAR(50),
    app_num VARCHAR(50),
    rec_num VARCHAR(10),
    pe_num VARCHAR(10),
    right_psn_art_upd_ymd VARCHAR(8),
    right_person_appl_id VARCHAR(50),
    right_person_addr_len INT,
    right_person_addr VARCHAR(255),
    right_person_name_len INT,
    right_person_name VARCHAR(255),
    PRIMARY KEY (law_cd, reg_num, pe_num),
    INDEX idx_reg_num (reg_num)
);
```

### 4.3. Bảng Detail Files với mu_num

```sql
-- upd_trnsfr_rcpt_info_p
CREATE TABLE upd_trnsfr_rcpt_info_p (
    processing_type INT,
    law_cd INT,
    reg_num VARCHAR(50),
    split_num VARCHAR(50),
    app_num VARCHAR(50),
    mrgn_info_upd_ymd VARCHAR(8),
    mu_num VARCHAR(10),
    trnsfr_rcpt_info TEXT,
    PRIMARY KEY (law_cd, reg_num, mu_num),
    INDEX idx_reg_num (reg_num)
);

-- upd_trnsfr_rcpt_info_u
CREATE TABLE upd_trnsfr_rcpt_info_u (
    -- tương tự như trên
    PRIMARY KEY (law_cd, reg_num, mu_num)
);
```

---

## 5. Flow xử lý dữ liệu

### 5.1. Flow tổng thể

```
1. Đọc danh sách folders (sorted theo ngày)
   └─> JPDRP_20241102 (folder đầu tiên)
   └─> JPDRP_20241106 (folder tiếp theo)
   └─> ...

2. Với mỗi folder:
   ├─> Đọc tất cả file .tsv trong folder/JPDRP/
   │
   ├─> Với mỗi file:
   │   ├─> Đọc header → xác định tên table và columns
   │   ├─> Đọc từng dòng dữ liệu
   │   │
   │   ├─> Nếu là folder đầu tiên:
   │   │   └─> INSERT tất cả records
   │   │
   │   └─> Nếu là folder tiếp theo:
   │       ├─> Xác định khóa duy nhất dựa trên loại file
   │       │   ├─> Master files: (law_cd, reg_num)
   │       │   ├─> Detail với pe_num: (law_cd, reg_num, pe_num)
   │       │   └─> Detail với mu_num: (law_cd, reg_num, mu_num)
   │       │
   │       └─> Thực hiện UPSERT:
   │           ├─> Nếu key tồn tại → UPDATE
   │           └─> Nếu key không tồn tại → INSERT
```

### 5.2. Chi tiết xử lý từng file

#### File Mapping và Key Detection:

```python
FILE_KEY_CONFIG = {
    # Master files (1 record per reg_num)
    'upd_mgt_info_p': {
        'table': 'upd_mgt_info_p',
        'key': ['law_cd', 'reg_num'],
        'type': 'master'
    },
    'upd_mgt_info_u': {
        'table': 'upd_mgt_info_u',
        'key': ['law_cd', 'reg_num'],
        'type': 'master'
    },
    'upd_mrgn_ext_app_num_p': {
        'table': 'upd_mrgn_ext_app_num_p',
        'key': ['law_cd', 'reg_num'],
        'type': 'master'
    },
    
    # Detail files với pe_num
    'upd_atty_art_p': {
        'table': 'upd_atty_art_p',
        'key': ['law_cd', 'reg_num', 'pe_num'],
        'type': 'detail_pe'
    },
    'upd_atty_art_u': {
        'table': 'upd_atty_art_u',
        'key': ['law_cd', 'reg_num', 'pe_num'],
        'type': 'detail_pe'
    },
    'upd_prog_info_div_p': {
        'table': 'upd_prog_info_div_p',
        'key': ['law_cd', 'reg_num', 'pe_num'],
        'type': 'detail_pe'
    },
    'upd_prog_info_div_u': {
        'table': 'upd_prog_info_div_u',
        'key': ['law_cd', 'reg_num', 'pe_num'],
        'type': 'detail_pe'
    },
    'upd_right_person_art_p': {
        'table': 'upd_right_person_art_p',
        'key': ['law_cd', 'reg_num', 'pe_num'],
        'type': 'detail_pe'
    },
    'upd_right_person_art_u': {
        'table': 'upd_right_person_art_u',
        'key': ['law_cd', 'reg_num', 'pe_num'],
        'type': 'detail_pe'
    },
    
    # Detail files với mu_num
    'upd_trnsfr_rcpt_info_p': {
        'table': 'upd_trnsfr_rcpt_info_p',
        'key': ['law_cd', 'reg_num', 'mu_num'],
        'type': 'detail_mu'
    },
    'upd_trnsfr_rcpt_info_u': {
        'table': 'upd_trnsfr_rcpt_info_u',
        'key': ['law_cd', 'reg_num', 'mu_num'],
        'type': 'detail_mu'
    }
}
```

---

## 6. Xử lý Edge Cases

### 6.1. File không tồn tại trong folder đầu tiên

**Ví dụ:** `upd_trnsfr_rcpt_info_u.tsv` chỉ có trong JPDRP_20241106

**Giải pháp:**
- Kiểm tra table tồn tại trong database
- Nếu table không tồn tại → tạo table mới
- INSERT tất cả records (coi như folder đầu tiên)

### 6.2. Cột mới xuất hiện ở folder sau ⚠️

**Kịch bản:** Folder tiếp theo có cùng tên file nhưng có thêm cột mới so với folder trước đó.

**Ví dụ:**
- Folder 1: `upd_mgt_info_p.tsv` có 35 cột
- Folder 2: `upd_mgt_info_p.tsv` có 37 cột (thêm 2 cột mới)

**Giải pháp chi tiết:**

#### Bước 1: Schema Detection (Phát hiện schema)

**Logic:**
```python
def detect_schema_changes(file_path, table_name, db_connection):
    """
    So sánh schema giữa file TSV và table trong database
    Returns: (new_columns, missing_columns)
    """
    # 1. Đọc header từ file TSV
    with open(file_path, 'r', encoding='utf-8') as f:
        header = f.readline().strip()
        file_columns = header.split('\t')
    
    # 2. Lấy danh sách cột hiện tại trong database
    existing_columns = get_table_columns(table_name, db_connection)
    
    # 3. So sánh
    new_columns = [col for col in file_columns if col not in existing_columns]
    missing_columns = [col for col in existing_columns if col not in file_columns]
    
    return new_columns, missing_columns

def get_table_columns(table_name, db_connection):
    """Lấy danh sách cột từ database"""
    # MySQL
    query = f"SHOW COLUMNS FROM {table_name}"
    # PostgreSQL
    # query = f"SELECT column_name FROM information_schema.columns WHERE table_name = '{table_name}'"
    
    cursor = db_connection.cursor()
    cursor.execute(query)
    return [row[0] for row in cursor.fetchall()]
```

#### Bước 2: Auto Migration (Tự động migrate schema)

**Chiến lược:**

**Option A: Tự động thêm cột mới (Recommended)**

```python
def migrate_table_schema(table_name, new_columns, db_connection):
    """
    Tự động thêm các cột mới vào table
    """
    cursor = db_connection.cursor()
    
    for column_name in new_columns:
        # Xác định kiểu dữ liệu dựa trên cột đầu tiên trong file
        column_type = infer_column_type(column_name, sample_data)
        
        try:
            alter_sql = f"ALTER TABLE {table_name} ADD COLUMN {column_name} {column_type}"
            cursor.execute(alter_sql)
            db_connection.commit()
            logger.info(f"Added column {column_name} to table {table_name}")
        except Exception as e:
            logger.error(f"Failed to add column {column_name}: {e}")
            # Có thể skip hoặc raise exception tùy theo yêu cầu

def infer_column_type(column_name, sample_data):
    """
    Suy luận kiểu dữ liệu từ tên cột và dữ liệu mẫu
    """
    # Heuristics dựa trên tên cột
    if 'num' in column_name.lower() or 'id' in column_name.lower():
        return 'VARCHAR(50)'
    elif 'date' in column_name.lower() or 'ymd' in column_name.lower():
        return 'VARCHAR(8)'
    elif 'len' in column_name.lower():
        return 'VARCHAR(50)'  # hoặc INT
    elif 'name' in column_name.lower() or 'addr' in column_name.lower():
        return 'VARCHAR(255)'
    elif 'info' in column_name.lower() or 'title' in column_name.lower():
        return 'TEXT'
    elif 'flg' in column_name.lower():
        return 'VARCHAR(10)'
    else:
        # Default: VARCHAR(255) để an toàn
        return 'VARCHAR(255)'
```

**Option B: Manual Review (An toàn hơn)**

```python
def handle_schema_changes(new_columns, table_name):
    """
    Log và yêu cầu review manual
    """
    if new_columns:
        logger.warning(f"New columns detected in {table_name}: {new_columns}")
        # Lưu vào file migration log
        with open('migration_log.json', 'a') as f:
            json.dump({
                'table': table_name,
                'new_columns': new_columns,
                'timestamp': datetime.now().isoformat(),
                'status': 'pending_review'
            }, f)
        
        # Có thể raise exception để dừng process hoặc continue với warning
        raise SchemaChangeException(f"Schema changed. New columns: {new_columns}")
```

#### Bước 3: Update Sync Logic để handle cột mới

**Cập nhật UPSERT logic:**

```python
def sync_file_to_table(file_path, table_name, is_first_folder, db_connection):
    """
    Sync file TSV vào table với xử lý schema changes
    """
    # 1. Detect schema changes (chỉ khi không phải folder đầu tiên)
    if not is_first_folder:
        new_columns, missing_columns = detect_schema_changes(
            file_path, table_name, db_connection
        )
        
        if new_columns:
            logger.info(f"New columns detected: {new_columns}")
            # Auto migrate hoặc manual review
            migrate_table_schema(table_name, new_columns, db_connection)
        
        if missing_columns:
            logger.warning(f"Missing columns in file: {missing_columns}")
            # Cột này sẽ được set NULL trong INSERT
    
    # 2. Lấy danh sách cột hiện tại sau khi migrate
    current_columns = get_table_columns(table_name, db_connection)
    
    # 3. Đọc file và sync
    with open(file_path, 'r', encoding='utf-8') as f:
        header = f.readline().strip().split('\t')
        
        # Chỉ lấy các cột có trong cả file và table
        columns_to_sync = [col for col in header if col in current_columns]
        
        # Build INSERT statement với các cột này
        placeholders = ','.join(['%s'] * len(columns_to_sync))
        columns_str = ','.join(columns_to_sync)
        
        insert_sql = f"""
        INSERT INTO {table_name} ({columns_str})
        VALUES ({placeholders})
        ON DUPLICATE KEY UPDATE
        {', '.join([f"{col} = VALUES({col})" for col in columns_to_sync if col not in PRIMARY_KEY_COLUMNS])}
        """
        
        # Process rows...
```

#### Bước 4: Handle Data Migration cho cột mới

**Quan trọng:** Khi thêm cột mới, các records cũ sẽ có giá trị NULL cho cột này.

**Option A: Giữ NULL (Recommended)**
- Các records cũ không có dữ liệu cho cột mới → để NULL
- Các records mới từ folder sau sẽ có giá trị

**Option B: Backfill từ folder mới**
```python
def backfill_new_columns(table_name, file_path, new_columns):
    """
    Update các records cũ với giá trị từ folder mới nếu có
    """
    # Đọc file và update các records cũ
    # Chỉ áp dụng nếu logic business cho phép
    pass
```

#### Bước 5: Schema Version Tracking

**Lưu schema version để tracking:**

```python
# Tạo table để track schema version
CREATE TABLE schema_versions (
    table_name VARCHAR(100),
    version INT,
    columns TEXT,  -- JSON array of column names
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (table_name, version)
);

def save_schema_version(table_name, columns):
    """Lưu schema version"""
    version = get_next_version(table_name)
    columns_json = json.dumps(columns)
    
    cursor.execute(
        "INSERT INTO schema_versions (table_name, version, columns) VALUES (?, ?, ?)",
        (table_name, version, columns_json)
    )
```

#### Flow hoàn chỉnh:

```
1. Đọc file TSV từ folder tiếp theo
   │
   ├─> Đọc header → Danh sách cột trong file
   │
   ├─> Lấy danh sách cột hiện tại trong database
   │
   ├─> So sánh schema
   │   ├─> Có cột mới? → ALTER TABLE ADD COLUMN
   │   │   └─> Log migration
   │   │
   │   └─> Có cột thiếu trong file? → Warning (set NULL)
   │
   ├─> Lấy lại danh sách cột sau khi migrate
   │
   └─> Sync data với columns đã được align
       ├─> INSERT/UPDATE với các cột có trong cả file và table
       └─> Records cũ: cột mới = NULL
           Records mới: cột mới = giá trị từ file
```

#### Best Practices:

1. **Always backup trước khi ALTER TABLE**
   ```python
   # Backup table trước khi migrate
   backup_table(table_name)
   ```

2. **Test trên staging trước**
   - Test với một vài records trước
   - Verify data integrity

3. **Logging đầy đủ**
   ```python
   logger.info(f"Schema migration: Table {table_name}")
   logger.info(f"  Added columns: {new_columns}")
   logger.info(f"  Migration SQL: {alter_sql}")
   ```

4. **Rollback plan**
   ```python
   def rollback_migration(table_name, columns_to_remove):
       """Rollback nếu cần"""
       for col in columns_to_remove:
           cursor.execute(f"ALTER TABLE {table_name} DROP COLUMN {col}")
   ```

5. **Notify stakeholders**
   - Thông báo khi có schema changes
   - Document changes trong migration log

#### Example Implementation:

```python
class SchemaMigrationHandler:
    def __init__(self, db_connection):
        self.db = db_connection
        self.migration_log = []
    
    def check_and_migrate(self, file_path, table_name):
        """Kiểm tra và migrate schema nếu cần"""
        file_columns = self.get_file_columns(file_path)
        db_columns = self.get_db_columns(table_name)
        
        new_columns = set(file_columns) - set(db_columns)
        missing_columns = set(db_columns) - set(file_columns)
        
        if new_columns:
            self.migrate_add_columns(table_name, new_columns, file_path)
        
        if missing_columns:
            logger.warning(f"Columns in DB but not in file: {missing_columns}")
        
        return list(file_columns), list(db_columns)
    
    def migrate_add_columns(self, table_name, new_columns, file_path):
        """Thêm các cột mới vào table"""
        # Đọc sample data để infer type
        sample_data = self.get_sample_data(file_path, new_columns)
        
        for col in new_columns:
            col_type = self.infer_column_type(col, sample_data.get(col))
            
            try:
                sql = f"ALTER TABLE {table_name} ADD COLUMN `{col}` {col_type} NULL"
                self.db.execute(sql)
                self.db.commit()
                
                self.migration_log.append({
                    'table': table_name,
                    'column': col,
                    'type': col_type,
                    'action': 'ADD',
                    'timestamp': datetime.now()
                })
                
                logger.info(f"✓ Added column {col} to {table_name}")
            except Exception as e:
                logger.error(f"✗ Failed to add column {col}: {e}")
                raise
```

#### Lưu ý quan trọng:

1. **Kiểu dữ liệu:** Phải suy luận đúng kiểu dữ liệu cho cột mới
2. **NULL handling:** Các records cũ sẽ có NULL cho cột mới
3. **Performance:** ALTER TABLE có thể lock table, nên làm khi ít traffic
4. **Data integrity:** Đảm bảo không mất dữ liệu khi migrate
5. **Testing:** Test kỹ trên staging trước khi áp dụng production

### 6.3. Cột bị thiếu ở folder sau

**Giải pháp:**
- INSERT với giá trị NULL cho cột thiếu
- Hoặc sử dụng giá trị mặc định

### 6.4. Encoding và ký tự đặc biệt

**Giải pháp:**
- Đảm bảo database hỗ trợ UTF-8
- Sử dụng charset=utf8mb4 cho MySQL
- Escape các ký tự đặc biệt khi insert

---

## 7. Tối ưu hóa Performance

### 7.1. Batch Insert

- Không insert từng record một
- Sử dụng batch insert (1000-10000 records/batch)
- Giảm số lần round-trip đến database

### 7.2. Transaction Management

- Sử dụng transaction cho mỗi batch
- Commit sau mỗi batch thành công
- Rollback nếu có lỗi

### 7.3. Indexing

- Tạo index trên `reg_num` để tăng tốc JOIN
- Tạo index trên các cột thường dùng để search
- Cân nhắc composite index nếu cần

### 7.4. Connection Pooling

- Sử dụng connection pool
- Giảm overhead của việc tạo/kết nối database

---

## 8. Validation và Error Handling

### 8.1. Validation trước khi insert

- Kiểm tra format của các trường ngày tháng
- Validate `law_cd` (chỉ 1 hoặc 2)
- Validate `reg_num` không null
- Validate các trường required không null

### 8.2. Error Handling

- Log lỗi chi tiết (file, dòng, lỗi)
- Tiếp tục xử lý các record khác nếu 1 record lỗi
- Lưu lại các record lỗi để xử lý sau

### 8.3. Data Integrity

- Kiểm tra foreign key constraints (nếu có)
- Đảm bảo `reg_num` trong detail files tồn tại trong master files
- Validate các giá trị enum/code

---

## 9. Monitoring và Logging

### 9.1. Metrics cần track

- Số records đã insert
- Số records đã update
- Số records lỗi
- Thời gian xử lý mỗi file
- Thời gian xử lý tổng thể

### 9.2. Logging

- Log start/end của mỗi folder
- Log start/end của mỗi file
- Log progress (mỗi N records)
- Log errors với context đầy đủ

---

## 10. Kết luận và Khuyến nghị

### 10.1. Khóa duy nhất đề xuất

| File Type | Khóa duy nhất |
|-----------|---------------|
| Master files | `(law_cd, reg_num)` |
| Detail với pe_num | `(law_cd, reg_num, pe_num)` |
| Detail với mu_num | `(law_cd, reg_num, mu_num)` |

### 10.2. Chiến lược INSERT/UPDATE

- **Khuyến nghị:** Sử dụng UPSERT (ON DUPLICATE KEY UPDATE)
- **Lý do:** Hiệu suất cao, atomic, đơn giản

### 10.3. Implementation Steps

1. **Bước 1:** Tạo database schema với PRIMARY KEY phù hợp
2. **Bước 2:** Implement file reader để đọc TSV
3. **Bước 3:** Implement logic xác định khóa duy nhất dựa trên tên file
4. **Bước 4:** Implement UPSERT logic
5. **Bước 5:** Implement batch processing
6. **Bước 6:** Implement error handling và logging
7. **Bước 7:** Test với folder đầu tiên
8. **Bước 8:** Test với folder tiếp theo (UPDATE scenario)

### 10.4. Database Platform

- **MySQL:** Sử dụng `ON DUPLICATE KEY UPDATE`
- **PostgreSQL:** Sử dụng `ON CONFLICT DO UPDATE`
- **SQLite:** Sử dụng `INSERT OR REPLACE` hoặc `ON CONFLICT`

---

**Tài liệu này cung cấp đầy đủ phân tích và hướng dẫn để implement hệ thống đồng bộ dữ liệu JPDRP vào database.**

