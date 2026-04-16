---
title: AI Assistant
author: ITE4116M Project Team
language: en-HK
---

# 3. AI Assistant

## 3.1 Purpose
This section explains how staff and admin users operate the AI Assistant page in VTC MyPortal.

The assistant provides conversational support for portal-related information retrieval and context-aware assistance across available data tools.

## 3.2 Audience
This guide is for:
- Teaching staff
- Administrative staff
- System or module administrators

## 3.3 Prerequisites
Before use:
- Sign in with a valid staff/admin account
- Ensure stable network connection
- Prepare clear task-oriented prompts

## 3.4 Interface Overview
The page is divided into:
1. Sidebar workspace controls
2. Main conversation workspace

### 3.4.1 Sidebar Controls
- Brand area
- **New Chat**
- **Chat History**
- **Back to Portal**

### 3.4.2 Conversation Workspace
- Header shows **New Chat** or **Chat Thread**
- Scrollable message timeline
- Input area with prompt textbox
- **Send** action button
- **Delete Thread** action (for active thread)

> Image placeholder: Assistant page UI map for staff/admin.

![Assistant interface overview](./images/03-assistant-overview.png)

## 3.5 Create a New Conversation
1. Open the Assistant module.
2. Confirm header displays **New Chat**.
3. Enter a clear prompt in the text area.
4. Select **Send**.
5. Wait for response generation to finish.

System behavior:
- A new chat thread is created on first send.
- The new thread is listed in **Chat History**.

> Image placeholder: New chat before and after first send.

![Create new conversation](./images/03-assistant-new-chat.png)

## 3.6 Use Chat History
1. Expand or review **Chat History**.
2. Select a thread to reopen previous context.
3. Continue with follow-up prompts.

This is useful for ongoing operational tasks requiring continuity.

> Image placeholder: Chat history list and selected thread.

![Chat history usage](./images/03-assistant-thread-history.png)

## 3.7 Prompting Best Practices for Staff/Admin
For consistent output quality:
1. Define the objective first
2. Include scope and context
3. Specify expected output format

Examples:
- "Summarize key student activity updates for this week in bullet points."
- "Provide a concise recap of recent announcements relevant to student affairs."
- "List timetable-related reminders for the current week."

## 3.8 Response and Streaming Behavior
After sending a prompt:
- User message is appended immediately.
- Assistant response is streamed progressively in the message area.
- The interface auto-scrolls to keep newest content visible.

Operational note:
- Avoid repeated submissions while a response is still streaming.

## 3.9 Delete a Thread
Use this when a conversation is no longer needed.

1. Open the target thread.
2. Select **Delete Thread**.
3. Confirm thread is removed from **Chat History** and session returns to assistant root state.

> Image placeholder: Delete thread action sequence.

![Delete thread flow](./images/03-assistant-delete-thread.png)

## 3.10 Return to Portal Dashboard
To leave assistant mode:
1. Select **Back to Portal** in the sidebar.
2. Verify redirection to portal home.

## 3.11 Troubleshooting
### Case A: Service Unavailable
- Assistant backend may be temporarily unavailable.
- Refresh and retry later.
- Escalate if persistent.

### Case B: Empty Submission Rejected
- Ensure the prompt text area is not blank.
- Re-enter question and select **Send**.

### Case C: Delayed or Incomplete Output
- Allow response stream to complete.
- Retry with smaller, more focused prompts.
- Reopen thread if UI state appears stale.

### Case D: Missing Thread in History
- Refresh the page or re-enter Assistant module.
- Verify first message submission completed successfully.

## 3.12 Security and Compliance Notes
- Do not submit credentials, confidential HR data, or sensitive records in prompts.
- Validate assistant output before administrative decision-making.
- Follow institutional data handling and access control policies.

## 3.13 Escalation Information
When reporting incidents, include:
- Staff/admin username
- Time and frequency of issue
- Affected thread name (if any)
- Screenshot and error context
- Browser, OS, and network environment
