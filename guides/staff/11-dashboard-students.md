---
title: Staff and Admin User Guide - Dashboard Students
author: ITE4116M Project Team
language: en-HK
---

# 11. Dashboard Students

## 11.1 Purpose
This chapter explains how staff/admin users manage student records in Dashboard.

Pages covered:
1. Students List
2. Student Create/Edit
3. Batch Import Students

## 11.2 Navigation Overview
From Dashboard navigation, open Student Management pages through:
- All Students
- Create Student
- Batch Import

> Image placeholder: Dashboard navigation routes to student pages.

![Dashboard students navigation](./images/11-dashboard-students-navigation.png)

## 11.3 Students List Page
The Students list page is the main workspace for searching, filtering, reviewing, editing, and deleting student records.

Primary features:
- Search by ID/name/mobile
- Filter drawer (Institute, Campus)
- Sortable table columns
- Row action menu (Edit/Delete)
- Pagination and page-size selector

Table columns:
- Student ID
- Name
- Institute
- Campus
- Classes
- Programmes

> Image placeholder: Students list page overview.

![Students list overview](./images/11-dashboard-students-list-overview.png)

## 11.4 Search, Filter, and Sort
### 11.4.1 Keyword Search
Use search input to find students by:
- Student ID
- Family/Given/Chinese name
- Other matching identity text

### 11.4.2 Filter Drawer
Open Filters to apply:
- Institute
- Campus (scoped by selected institute)

Drawer actions:
- Reset
- Done

### 11.4.3 Sorting and Pagination
- Sort supported by Student ID and Name columns.
- Pagination supports multiple page sizes (5, 10, 25).

Operational note:
- Name display behavior is locale-aware (Chinese locale prioritizes Chinese name when available).

> Image placeholder: Search/filter/sort controls in use.

![Students filter and sort](./images/11-dashboard-students-list-filters.png)

## 11.5 Row Actions
Each row provides:
- Edit action
- Delete action

On smaller screens, actions appear in overflow menu.

Delete behavior:
- Successful delete shows confirmation message.
- If record is in use, deletion is blocked with error message.

## 11.6 Student Create/Edit Page
Use this page to create a new student or update an existing student profile.

Page mode labels:
- Create Student
- Update Student

> Image placeholder: Student create/edit page overview.

![Student edit page](./images/11-dashboard-students-edit-overview.png)

## 11.7 Student Form Fields
Core identity fields:
- Student ID
- Chinese Name (optional)
- Family Name
- Given Name

Personal profile fields:
- Gender
- Date of Birth
- Nationality
- Mother Tongue
- Telephone No.
- Mobile No.
- Address

Academic assignment fields:
- Institute
- Campus
- Programme
- Classes (multi-select)

Dependency behavior:
- Institute controls available campus and programme options.
- Classes list is available only when institute, campus, and programme are selected.
- Changing higher-level selections may clear incompatible lower-level selections.

## 11.8 Save Behavior and Validation
Validation highlights:
- Student ID required and unique in users table.
- Institute, campus, and programme must match valid relationships.
- Selected classes must belong to selected institute/campus/programme.

Save outcomes:
- Update mode: Student was updated.
- Create mode: Student was created and password is auto-generated.

## 11.9 Batch Import Students Page
Batch Import supports CSV-based bulk student creation.

Key components:
- CSV file upload input
- Download Sample CSV button
- Import summary stats (Processed/Imported/Skipped)
- Import log table (row-level result)

> Image placeholder: Batch import page overview.

![Batch import students overview](./images/11-dashboard-students-import-overview.png)

## 11.10 CSV Requirements
Accepted upload:
- CSV/TXT MIME-compatible file
- Maximum upload size: 5 MB

Header requirement:
- Header must exactly match expected template.
- Password column is not allowed.

Expected CSV columns:
- username
- family_name
- given_name
- chinese_name
- gender
- date_of_birth
- nationality
- mother_tongue
- tel_no
- mobile_no
- address
- institute_id
- campus_id
- programme_id
- class_ids

Use Download Sample CSV to avoid header mismatch.

## 11.11 Import Processing and Results
During import:
- Rows are validated individually.
- Valid rows are inserted with auto-generated password and student role.
- Invalid rows are skipped.

After import:
- Summary displays processed, imported, and skipped row counts.
- Import Log shows per-row status and message.

Typical log statuses:
- Imported
- Skipped

## 11.12 Typical Staff/Admin Workflows
### Workflow A: Find and Edit Student Record
1. Open Students list.
2. Search by Student ID or name.
3. Use filters for institute/campus if needed.
4. Select Edit on target row.
5. Update fields and save.

### Workflow B: Create New Student Manually
1. Open Create Student.
2. Fill identity and profile fields.
3. Select institute, campus, programme, and class(es).
4. Save and verify success message.

### Workflow C: Bulk Onboard Students via CSV
1. Open Batch Import.
2. Download sample template.
3. Fill CSV with valid data and exact headers.
4. Upload and run import.
5. Review summary and row logs.
6. Correct skipped rows and retry in a new CSV.

### Workflow D: Remove Student Record
1. Open Students list.
2. Select Delete.
3. Confirm removal or resolve dependency error if record is in use.

## 11.13 Troubleshooting
### Case A: Campus or Programme Not Available
- Ensure institute is selected first.
- Confirm campus/programme belongs to selected institute.

### Case B: Class Options Not Showing
- Confirm institute, campus, and programme are all selected.
- Check whether classes exist for that combination.

### Case C: Import Rejected with Invalid Header
- Use sample CSV template.
- Ensure exact header names and order.

### Case D: Import Error About Password Column
- Remove password column from CSV.
- Password is generated automatically by system.

### Case E: Many Rows Skipped in Import
- Review Import Log messages row by row.
- Correct IDs/relationships (institute, campus, programme, class).
- Retry import with cleaned CSV.

### Case F: Delete Student Fails
- Student record is linked to other data.
- Resolve dependencies before deletion.

## 11.14 Data and Security Notes
- Student profiles contain personal data and must be handled according to policy.
- Do not share import files or logs outside authorized channels.
- Validate student identity fields before saving.
- Keep CSV files in secure storage and remove temporary copies when done.

## 11.15 Escalation Information
When reporting Dashboard Students issues, include:
- Username and role (staff/admin)
- Page name (list/edit/import)
- Action performed
- Inputs used (Student ID, filters, CSV header sample)
- Exact error message and affected row number (for import)
- Timestamp, screenshot, browser, and OS details
