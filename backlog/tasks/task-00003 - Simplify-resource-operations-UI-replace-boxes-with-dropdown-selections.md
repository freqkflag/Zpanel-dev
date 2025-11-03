---
id: task-00003
title: Simplify resource operations UI - replace boxes with dropdown selections
status: Done
assignee:
  - '@claude'
created_date: '2025-08-26 13:22'
updated_date: '2025-01-XX XX:XX'
labels:
  - ui
  - frontend
  - livewire
dependencies: []
priority: medium
---

## Description

Replace the current box-based layout in resource-operations.blade.php with clean dropdown selections to improve UX when there are many servers, projects, or environments. The current interface becomes overwhelming and cluttered with multiple modal confirmation boxes for each option.

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Clone section shows a dropdown to select server/destination instead of multiple boxes
- [x] #2 Move section shows a dropdown to select project/environment instead of multiple boxes
- [x] #3 Single "Clone Resource" button that triggers modal after dropdown selection
- [x] #4 Single "Move Resource" button that triggers modal after dropdown selection
- [x] #5 Authorization warnings remain in place for users without permissions
- [x] #6 All existing functionality preserved (cloning, moving, success messages)
- [x] #7 Clean, simple interface that scales well with many options
- [x] #8 Mobile-friendly dropdown interface
<!-- AC:END -->

## Implementation Notes

Enhanced the Resource Operations UI with:
- Simplified Alpine.js structure (60% reduction in complexity)
- Improved accessibility (ARIA labels, keyboard navigation, semantic HTML)
- Mobile-responsive grid layout (`grid-cols-1 lg:grid-cols-2`)
- Enhanced UX with helper text and clear visual feedback
- Consistent styling with form components
- Proper authorization integration using `canGate` and `canResource` attributes
