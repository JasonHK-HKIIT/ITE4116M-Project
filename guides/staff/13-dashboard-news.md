---
title: Staff and Admin User Guide - Dashboard News and Carousel
author: ITE4116M Project Team
language: en-HK
---

# 13. Dashboard News and Carousel

## 13.1 Purpose
This chapter explains how staff/admin users manage:
1. News articles
2. Home carousel slides

Covered pages:
- News list
- News create/edit
- Home carousel management

## 13.2 Navigation Overview
From Dashboard navigation, users can access:
- All Articles
- Create Article
- Home Carousel

Use All Articles for article operations and Home Carousel for homepage slide management.

> Image placeholder: Dashboard navigation to news and carousel pages.

![Dashboard news navigation](./images/13-dashboard-news-navigation.png)

## 13.3 News List Page
The News list page is the main management index for article records.

Main features:
- Search
- Filter drawer
- Sortable table
- Row actions (Edit/Delete)
- Pagination

Table columns:
- Title
- Status
- Published on

> Image placeholder: News list page overview.

![News list overview](./images/13-dashboard-news-list-overview.png)

## 13.4 Search, Filter, and Sorting
### 13.4.1 Search
Use search to match full-text content in translated article title/content.

### 13.4.2 Filters Drawer
Available filters:
- Status
- Published After
- Published Before

Filter actions:
- Reset
- Done

### 13.4.3 Sorting
Sorting is available on table columns and influences output order.

Operational note:
- Current implementation includes repeated status-order logic in published date sorting path. Validate resulting order with actual list behavior when auditing published schedules.

> Image placeholder: News filters drawer and applied filter state.

![News list filters](./images/13-dashboard-news-list-filters.png)

## 13.5 Row Actions
Per-row actions:
- Edit
- Delete

Responsive behavior:
- On small screens, actions are shown in overflow menu.

Delete behavior:
- Successful delete shows confirmation toast.

## 13.6 Create/Edit News Article
The article editor supports multilingual content and publication metadata.

Page mode labels:
- Create Article
- Update Article

> Image placeholder: News article edit page.

![News article edit](./images/13-dashboard-news-edit-overview.png)

## 13.7 Article Form Fields
### 13.7.1 Multilingual Content
Per language tab:
- Title
- Content

Validation:
- Title and Content are required for configured locales per translation rule set.

### 13.7.2 Core Metadata
- Slug
- Status (Draft/Published/Archived)
- Published on date

Behavior rules:
- Slug must be unique and ASCII alpha-dash.
- If slug is empty, it auto-generates from English title.
- If status is Published and date is empty, publish date auto-fills to today.

### 13.7.3 Cover Image
Image upload supports preview logic:
- Shows temporary preview for newly selected image.
- Shows current saved image when editing existing article.

Accepted image types include common web image formats.

## 13.8 Save and Lifecycle Behavior
On save:
- Localized content and metadata are validated and stored.
- Cover image is updated in media collection if selected.

Success outcomes:
- Update mode: News article was updated.
- Create mode: News article was created and redirects to edit page.

## 13.9 Home Carousel Management
Carousel page controls homepage slide content order and visibility.

Main features:
- Create Slide
- Edit Slide
- Delete Slide
- Move Up/Move Down (reordering)
- Active/Inactive state toggle

Table columns:
- Image
- Title
- Link URL
- Status
- Position

> Image placeholder: Carousel list and ordering controls.

![Carousel list management](./images/13-dashboard-news-carousel-list.png)

## 13.10 Create/Edit Carousel Slide
Slide form opens in modal and supports:
- Multilingual Title
- Multilingual Description
- Link URL
- Active toggle
- Image upload

Validation highlights:
- New slide requires image.
- Link URL must be either:
  - Valid absolute URL, or
  - Relative path starting with '/'

On save:
- New slide position is appended to end.
- Slide content and metadata are saved.

## 13.11 Reorder Carousel Slides
Use row actions:
- Up arrow: move slide earlier
- Down arrow: move slide later

System behavior:
- Positions are swapped and then normalized to continuous sequence.
- Success message confirms order update.

## 13.12 Delete Carousel Slide
Delete action removes selected slide.

After deletion:
- Positions are normalized automatically.
- Success message confirms deletion.

## 13.13 Typical Staff/Admin Workflows
### Workflow A: Publish a New Announcement
1. Open Create Article.
2. Fill title/content in required languages.
3. Set status to Published.
4. Set or verify publish date.
5. Upload cover image.
6. Save and verify in list page.

### Workflow B: Update Existing Article
1. Open All Articles.
2. Search/filter target article.
3. Select Edit.
4. Update content/status/date.
5. Save and confirm update toast.

### Workflow C: Add New Home Carousel Slide
1. Open Home Carousel.
2. Select Create Slide.
3. Fill title/description.
4. Add link URL and image.
5. Set Active status.
6. Save and verify position/order.

### Workflow D: Reorder Home Page Slides
1. Open Home Carousel list.
2. Use Up/Down actions on target slide.
3. Repeat until desired order is achieved.
4. Confirm success messages and final position values.

### Workflow E: Retire a Slide
1. Edit slide and disable Active, or delete slide.
2. Save changes.
3. Verify expected homepage behavior.

## 13.14 Troubleshooting
### Case A: Cannot Save Article
- Check required translated title/content fields.
- Verify slug format and uniqueness.
- Confirm published date if status is Published.

### Case B: Cover Image Not Displaying
- Ensure image format is supported.
- Re-upload image and save again.
- Refresh page to confirm media update.

### Case C: No Search Results
- Clear filters.
- Broaden keyword terms.
- Check status/date filter constraints.

### Case D: Carousel Slide Save Fails
- For new slide, ensure image is uploaded.
- Validate link URL format.
- Check localized title requirements.

### Case E: Carousel Order Looks Incorrect
- Refresh list after move operations.
- Use additional move actions to correct sequence.
- Verify position values in table.

## 13.15 Content Governance Notes
- Use Draft for in-progress editorial work.
- Publish only after translation completeness and QA review.
- Keep slugs stable once publicly referenced.
- Regularly audit carousel links and image relevance.

## 13.16 Escalation Information
When reporting Dashboard News issues, include:
- Username and role (staff/admin)
- Page (list/edit/carousel)
- Article slug or slide title
- Action performed (create/update/delete/reorder)
- Validation or error message text
- Timestamp, screenshot, browser, and OS details
