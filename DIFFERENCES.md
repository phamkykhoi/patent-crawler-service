# Danh sách sự khác biệt giữa Laravel Migrations và Prisma Schema

## 1. KHÁC BIỆT VỀ TÊN BẢNG (Table Names)

### Các bảng trong Migrations KHÔNG có prefix `upd_` nhưng trong Prisma CÓ:
- `sinseinin` (migration) → `upd_sinseinin` (Prisma)
- `cmis_g_intl_app_case_stat` (migration) → `upd_cmis_g_intl_app_case_stat` (Prisma)
- `pmac_g_app_case` (migration) → `upd_pmac_g_app_case` (Prisma)
- `umac_g_app_case` (migration) → `upd_umac_g_app_case` (Prisma)
- `pmab_g_appl_case_biblog` (migration) → `upd_pmab_g_appl_case_biblog` (Prisma)
- `umab_g_appl_case_biblog` (migration) → `upd_umab_g_appl_case_biblog` (Prisma)
- `pmcs_g_case_stat` (migration) → `upd_pmcs_g_case_stat` (Prisma)
- `umcs_g_case_stat` (migration) → `upd_umcs_g_case_stat` (Prisma)
- `pmjb_g_jpo_case_biblog` (migration) → `upd_pmjb_g_jpo_case_biblog` (Prisma)
- `umjb_g_jpo_case_biblog` (migration) → `upd_umjb_g_jpo_case_biblog` (Prisma)
- `cmbi_g_bul_info` (migration) → `upd_cmbi_g_bul_info` (Prisma)
- `pmjb_gr_jpo_case_biblog` (migration) → `upd_pmjb_gr_jpo_case_biblog` (Prisma)
- `umjb_gr_jpo_case_biblog` (migration) → `upd_umjb_gr_jpo_case_biblog` (Prisma)
- `pmab_gr_appl_case_biblog` (migration) → `upd_pmab_gr_appl_case_biblog` (Prisma)
- `umab_gr_appl_case_biblog` (migration) → `upd_umab_gr_appl_case_biblog` (Prisma)
- `pmap_g_app_doc` (migration) → `upd_pmap_g_app_doc` (Prisma)
- `umap_g_app_doc` (migration) → `upd_umap_g_app_doc` (Prisma)
- `cmia_g_intl_app_doc` (migration) → `upd_cmia_g_intl_app_doc` (Prisma)
- `pmoa_g_old_app_case` (migration) → `upd_pmoa_g_old_app_case` (Prisma)
- `pmob_g_old_case_biblog` (migration) → `upd_pmob_g_old_case_biblog` (Prisma)
- `pmos_g_old_case_stat` (migration) → `upd_pmos_g_old_case_stat` (Prisma)
- `pmac_gr_app_case_repeat_data` (migration) → `upd_pmac_gr_app_case_repeat_data` (Prisma)

### Các bảng trong Migrations CÓ prefix `upd_` và trong Prisma CŨNG CÓ (giống nhau):
- `upd_mgt_info_p`
- `upd_mgt_info_u`
- `upd_mrgn_ext_app_num_p`
- `upd_atty_art_p`
- `upd_atty_art_u`
- `upd_prog_info_div_p`
- `upd_prog_info_div_u`
- `upd_right_person_art_p`
- `upd_right_person_art_u`
- `upd_trnsfr_rcpt_info_p`
- `upd_trnsfr_rcpt_info_u`

## 2. BẢNG THIẾU TRONG PRISMA SCHEMA

Các bảng sau có trong Migrations nhưng KHÔNG có trong Prisma Schema:
- `umoa_g_old_app_case` (migration: 2025_01_01_000022)
- `umob_g_old_case_biblog` (migration: 2025_01_01_000023)
- `umos_g_old_case_stat` (migration: 2025_01_01_000024)

## 3. BẢNG THIẾU TRONG MIGRATIONS

Các bảng sau có trong Prisma Schema nhưng KHÔNG có migration tương ứng:
- `migrations` (bảng Laravel migrations - có thể được tạo tự động)

## 4. KHÁC BIỆT VỀ KIỂU DỮ LIỆU ID (Primary Key)

### Bảng sử dụng `$table->id()` (auto-increment integer) trong Migrations nhưng UUID trong Prisma:
- `upd_mgt_info_p` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_mgt_info_u` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_mrgn_ext_app_num_p` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_atty_art_p` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_atty_art_u` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_prog_info_div_p` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_prog_info_div_u` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_right_person_art_p` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_right_person_art_u` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_trnsfr_rcpt_info_p` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`
- `upd_trnsfr_rcpt_info_u` - Migration: `$table->id()`, Prisma: `String @id @default(dbgenerated("gen_random_uuid()")) @db.Uuid`

### Bảng sử dụng UUID trong cả Migrations và Prisma (giống nhau):
- Tất cả các bảng khác (sinseinin, cmis_g_intl_app_case_stat, pmac_g_app_case, v.v.)

## 5. KHÁC BIỆT VỀ KIỂU DỮ LIỆU CỘT (Column Types)

### String với độ dài cụ thể trong Migrations vs Text trong Prisma:
- Migrations: `string('app_num', 50)`, `char('delete_flg', 1)`, `string('update_dttm', 14)`
- Prisma: Tất cả đều là `String? @db.Text` hoặc `String @db.Text`

### Ví dụ cụ thể:
- `app_num`: Migration `string('app_num', 50)` → Prisma `String @db.Text`
- `ac_delete_flg`: Migration `char('ac_delete_flg', 1)` → Prisma `String? @db.Text`
- `ac_update_dttm`: Migration `string('ac_update_dttm', 14)` → Prisma `String? @db.Text`
- `sinseinin_code`: Migration `string('sinseinin_code', 50)` → Prisma `String @db.Text`

## 6. KHÁC BIỆT VỀ INDEX NAMES

Một số index có tên khác nhau:
- `pmac_g_app_case`: Migration `idx_app_num` → Prisma `idx_upd_pmac_g_app_case_app_num`
- `cmis_g_intl_app_case_stat`: Migration `idx_intl_app_num` → Prisma `idx_upd_cmis_g_intl_app_case_stat_intl_app_num`
- `sinseinin`: Migration `idx_sinseinin_code` → Prisma `idx_upd_sinseinin_code`

## 7. KHÁC BIỆT VỀ UNIQUE CONSTRAINT NAMES

- `pmac_g_app_case`: Migration không có tên constraint → Prisma có `map: "upd_pmac_g_app_case_main_ids"`
- `cmis_g_intl_app_case_stat`: Migration không có tên constraint → Prisma có `map: "upd_cmis_g_intl_app_case_stat_main_ids"`

## TÓM TẮT

1. **Tên bảng**: Hầu hết các bảng trong migrations không có prefix `upd_` nhưng trong Prisma có
2. **Bảng thiếu**: 3 bảng `umoa_g_old_app_case`, `umob_g_old_case_biblog`, `umos_g_old_case_stat` có trong migrations nhưng không có trong Prisma
3. **ID type**: 11 bảng `upd_*` sử dụng auto-increment integer trong migrations nhưng UUID trong Prisma
4. **Column types**: Migrations sử dụng string với độ dài cụ thể, Prisma sử dụng Text
5. **Index/Constraint names**: Một số có tên khác nhau hoặc thiếu tên trong migrations


