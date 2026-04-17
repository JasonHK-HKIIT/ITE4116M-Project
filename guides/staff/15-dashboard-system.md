---
title: Staff and Admin User Guide - Dashboard System Management
author: ITE4116M Project Team
language: en-HK
---

# 15. Dashboard System Management

## 15.1 Purpose
This chapter explains how staff/admin users manage system-level user operations in Dashboard.

Pages covered:
1. Password Reset
2. Staff Members List
3. Staff Member Create/Edit

## 15.2 Navigation Overview
From Dashboard System Management area, users can access:
- Staff Members
- Password Reset

Staff Members controls staff/admin account records and permissions.
Password Reset allows admins/staff (with access) to reset non-admin account passwords.

> Image placeholder: Dashboard system management navigation.

![Dashboard system navigation](./images/15-dashboard-system-navigation.png)

## 15.3 Password Reset Page
Page title: Password Reset
Subtitle: Reset password for non-admin users

Purpose:
- Reset a user password by username
- Restricted against resetting admin accounts

Form fields:
- Username
- New Password
- Confirm New Password

Action:
- Reset Password button

> Image placeholder: Password reset page with completed form.

![Password reset page](./images/15-dashboard-system-password-reset.png)

## 15.4 Password Reset Rules and Outcomes
Validation and behavior:
- Username must exist.
- Password must pass password validation policy.
- Confirm New Password must match New Password.

System constraints:
- If username not found, User not found message appears.
- If target user is admin, reset is blocked with Admin accounts cannot be reset from this page.

Success result:
- Password has been reset successfully.

Operational note:
- This page is designed for non-admin account reset only.

## 15.5 Staff Members List Page
Page title: Staff Members
Subtitle: System User Management

Purpose:
- Browse and maintain staff/admin accounts
- Filter by role and permission
- Open create/edit workflows

Main features:
- Search by username or name
- Filter drawer
- Sortable table
- Row actions (Edit/Delete)
- Pagination

Table columns:
- Username
- Name
- Role
- Permissions
- Created

> Image placeholder: Staff members list overview.

![Staff members list](./images/15-dashboard-system-staff-list-overview.png)

## 15.6 Search, Filters, and Sorting
### 15.6.1 Search
Use search field to find staff records by username or name.

### 15.6.2 Filter Drawer
Available filters:
- Role
- Permission

Permission filter behavior:
- Admin users are treated as full-access records and may appear when filtering by permission logic.

Drawer actions:
- Reset
- Done

### 15.6.3 Sorting
Supported sort keys include:
- Username
- Name
- Role
- Created date

Locale note:
- Name sort/display logic adapts to locale preference (Chinese vs non-Chinese name priority).

> Image placeholder: Staff filters drawer and active results.

![Staff list filters](./images/15-dashboard-system-staff-list-filters.png)

## 15.7 Role and Permission Display
Role badges:
- Admin
- Staff

Permissions column behavior:
- Admin: shows All Permissions badge
- Staff: shows assigned permission badges
- No assigned permissions: displays placeholder

## 15.8 Staff List Row Actions
Per-row actions:
- Edit
- Delete

Responsive behavior:
- On small screens, actions are available via overflow menu.

Delete safeguards:
- You cannot delete your own account.
- If record is in use, deletion is blocked with error message.

## 15.9 Staff Member Create/Edit Page
Use this page to create or update staff/admin accounts.

Page subtitles:
- Create Staff Member
- Update Staff Member

> Image placeholder: Staff create/edit page overview.

![Staff edit page](./images/15-dashboard-system-staff-edit-overview.png)

## 15.10 Staff Form Fields
Identity fields:
- Username
- Chinese Name (optional)
- Family Name
- Given Name

Access control fields:
- Role (Staff/Admin)
- Permissions (multi-select)

Role-dependent behavior:
- If role is Admin, permission selector is disabled and system treats account as full access.
- If role is Staff, selected permissions are stored explicitly.

## 15.11 Save and Validation Rules
Validation highlights:
- Username required, unique, ASCII alpha-dash format.
- Family Name and Given Name required.
- Role must be Staff or Admin.
- Permissions must be valid system permission keys.

Update safeguard:
- Current logged-in admin cannot downgrade their own role from Admin.

Create behavior:
- New staff account receives auto-generated password.

Success messages:
- Staff member was created. A password has been auto-generated.
- Staff member was updated.

## 15.12 Typical Staff/Admin Workflows
### Workflow A: Create New Staff Account
1. Open Staff Members list.
2. Select Create.
3. Fill identity fields.
4. Choose role.
5. If Staff role, assign permission set.
6. Save and confirm success message.

### Workflow B: Promote Staff to Admin
1. Open target staff record in Edit mode.
2. Change role to Admin.
3. Save.
4. Confirm permissions are now implicit full access.

### Workflow C: Narrow Staff Permissions
1. Open target staff record.
2. Ensure role is Staff.
3. Adjust permissions multi-select.
4. Save.

### Workflow D: Reset Non-Admin User Password
1. Open Password Reset page.
2. Enter target username.
3. Enter and confirm new password.
4. Submit and verify success toast.

### Workflow E: Remove Staff Account
1. Open Staff Members list.
2. Select Delete on target record.
3. Confirm deletion result.
4. If blocked, review usage dependencies.

## 15.13 Troubleshooting
### Case A: Password Reset Fails
- Verify username exists.
- Confirm target is not admin role.
- Recheck password policy compliance and confirmation match.

### Case B: Cannot Delete Staff Record
- If deleting your own account, action is blocked by design.
- If in use, resolve linked dependencies first.

### Case C: Permissions Not Editable
- Check role field.
- Permissions are intentionally disabled for Admin role.

### Case D: Cannot Change Own Role
- System blocks self-demotion from Admin.
- Use another admin account for role changes if necessary.

### Case E: Filter Results Seem Unexpected
- Clear filters using Reset.
- Reapply role and permission filters step by step.

## 15.14 Security and Governance Notes
- Restrict Password Reset access to authorized operators.
- Apply least-privilege principle for staff permissions.
- Avoid using Admin role unless operationally required.
- Audit staff account changes regularly.

## 15.15 Escalation Information
When reporting System Management issues, include:
- Username and role (staff/admin)
- Page (password reset, staff list, staff edit)
- Action attempted
- Target account username
- Exact error message
- Timestamp, screenshot, browser, and OS details
