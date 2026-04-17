---
title: Staff and Admin User Guide - Dashboard Activities
author: ITE4116M Project Team
language: en-HK
---

# 12. Dashboard Activities

## 12.1 Purpose
This chapter explains how staff/admin users manage Student Activities in Dashboard.

Pages covered:
1. Activities List
2. Create/Edit Activity

## 12.2 Navigation Overview
From Dashboard navigation, activities management is available through:
- All Activities
- Create Activities

The list page is the operational index for filtering and record actions, while the edit page handles full activity configuration.

> Image placeholder: Dashboard navigation to Activities module.

![Dashboard activities navigation](./images/12-dashboard-activities-navigation.png)

## 12.3 Activities List Page
The Activities list page displays activity records with search, filters, sorting, and row actions.

Main page elements:
- Search input
- Filters drawer
- Activities table
- Per-row actions (Details, Edit, Delete)
- Pagination and page-size control

Table columns:
- Title
- Status
- Instructor
- Registration Start Date
- Registration End Date

> Image placeholder: Activities list overview.

![Activities list overview](./images/12-dashboard-activities-list-overview.png)

## 12.4 Search, Filter, and Sorting
### 12.4.1 Search
Use search to match activity title/description keywords.

### 12.4.2 Filters Drawer
Available filters:
- Status
- Registration Start Date
- Registration End Date

Drawer actions:
- Reset
- Done

Filter indicator:
- Filter button shows a badge when any date/status filter is active.

### 12.4.3 Sorting
List supports sorting by:
- Title (translated ordering)
- Instructor
- Registration Start Date
- Registration End Date

Operational note:
- Date sorting logic is toggled by sort direction and can appear inverted compared with typical ascending/descending expectations.

> Image placeholder: Filter drawer and active filter badge.

![Activities filters and status](./images/12-dashboard-activities-list-filters.png)

## 12.5 Row Actions
Each row provides:
- Details: opens portal activity detail view
- Edit: opens dashboard edit form
- Delete: removes activity record

Responsive behavior:
- On small screens, actions move into overflow menu.

Delete behavior:
- Successful deletion shows confirmation message.

## 12.6 Create/Edit Activity Page
The activity form supports multilingual fields and comprehensive event configuration.

Page mode:
- Create Activity
- Update Activity

> Image placeholder: Activity edit page full layout.

![Activity edit overview](./images/12-dashboard-activities-edit-overview.png)

## 12.7 Form Structure
The form is organized by language tabs and shared configuration section.

### 12.7.1 Multilingual Fields
Per language tab:
- Title
- Description
- Venue Remark

Validation note:
- Primary locale title and description are required.

### 12.7.2 Core Activity Fields
- Activity Type
- Activity Code
- Status (Draft/Published/Archived)
- Campus
- Discipline
- Attribute
- Instructor
- Responsible Staff

### 12.7.3 Registration Period
- Registration Start Date
- Registration End Date

Validation rule:
- End date must be same as or after start date.

### 12.7.4 Time Slot and Duration
- Time Slot From (Date/Time)
- Time Slot To (Date/Time)
- Duration (hours)

Behavior:
- Duration auto-calculates when slot fields change.

### 12.7.5 Programme/Venue/Capacity/Financial
- SWPD Programme (checkbox)
- Venue
- Capacity
- Registered
- Total Amount
- Included Deposit

Validation notes:
- Registered cannot exceed Capacity.
- Included Deposit cannot exceed Total Amount.

## 12.8 Attachment Management
Supported upload formats include office and PDF documents.

Attachment workflow:
1. Upload file in attachment field.
2. File is validated and stored.
3. Existing file block displays file name and actions.
4. Optional actions:
   - View file
   - Delete file (with confirmation)

Create-mode behavior:
- Uploaded file is temporarily staged and moved to activity folder during save.

Update-mode behavior:
- Existing file can be replaced or deleted.

> Image placeholder: Attachment upload and file action block.

![Activity attachment management](./images/12-dashboard-activities-attachment.png)

## 12.9 Save Behavior
On save:
- Form data is validated.
- Localized content and scalar fields are transformed and persisted.
- Attachment references are synchronized.

Success outcomes:
- Update mode: Activity was updated.
- Create mode: Activity was created and redirects back to list.

## 12.10 Typical Staff/Admin Workflows
### Workflow A: Create New Activity Draft
1. Open Create Activities.
2. Complete core metadata and registration period.
3. Fill required localized title/description.
4. Configure time slot and capacity/financial fields.
5. Save as Draft.

### Workflow B: Publish Existing Activity
1. Open activity in Edit mode.
2. Update status to Published.
3. Verify registration period and details.
4. Save changes.

### Workflow C: Update Venue or Instructor
1. Open list and search target record.
2. Select Edit.
3. Update Venue/Instructor fields.
4. Save and validate result in list or details view.

### Workflow D: Replace Supporting File
1. Open activity edit page.
2. Upload new attachment.
3. Confirm file name update.
4. Remove outdated file if needed.
5. Save.

### Workflow E: Remove Activity
1. Locate activity on list.
2. Select Delete from row action.
3. Confirm removal through success message.

## 12.11 Troubleshooting
### Case A: Cannot Save Activity
- Check required fields in active locale.
- Verify date order and numeric constraints.
- Confirm Registered is not greater than Capacity.

### Case B: Duration Not Updating
- Ensure all four slot inputs are populated.
- Confirm end timestamp is after start timestamp.

### Case C: Attachment Upload Fails
- Verify file type and size limits.
- Retry upload with supported format.
- Check storage permissions if persistent.

### Case D: Status Filter Returns No Results
- Clear filters using Reset.
- Reapply one filter at a time.
- Confirm activities exist for selected date range/status.

### Case E: Wrong Locale Content Appears
- Switch language tab and verify content entry per locale.
- Ensure required primary-locale fields are present.

## 12.12 Operational Notes
- Use Draft status for incomplete records.
- Review registration window carefully before publishing.
- Keep attachment versions controlled and remove obsolete files.
- Validate financial fields against institutional policy before release.

## 12.13 Escalation Information
When reporting Dashboard Activities issues, provide:
- Username and role (staff/admin)
- Page (list or edit)
- Activity code/title
- Steps performed and values entered
- Error message text
- Timestamp, screenshot, browser, and OS details
