// @ts-check
import { defineConfig } from '@vivliostyle/cli';

export default defineConfig([
    {
        title: "VTC MyPortal User Guide for Students",
        author: "VTC MyPortal Team",
        language: "en",
        theme: "@vivliostyle/theme-techbook@^2.0.1",
        browser: "chrome@146.0.7680.153",
        image: "ghcr.io/vivliostyle/cli:10.5.0",
        toc: true,
        entry: [
            { rel: "contents" },
            "student/01-login.md",
            "student/02-home.md",
            "student/03-assistant.md",
            "student/04-calendar.md",
            "student/05-profile.md",
            "student/06-activities.md",
            "student/07-news.md",
            "student/08-resources.md",
        ],
        output: "guide-student.pdf",
    },
    {
        title: "VTC MyPortal User Guide for Staff/Admin",
        author: "VTC MyPortal Team",
        language: "en",
        theme: "@vivliostyle/theme-techbook@^2.0.1",
        browser: "chrome@146.0.7680.153",
        image: "ghcr.io/vivliostyle/cli:10.5.0",
        toc: true,
        entry: [
            { rel: "contents" },
            "staff/01-login.md",
            "staff/02-home.md",
            "staff/03-assistant.md",
            "staff/04-calendar.md",
            "staff/05-news.md",
            "staff/06-resources.md",
        ],
        output: "guide-staff.pdf",
    },
]);
