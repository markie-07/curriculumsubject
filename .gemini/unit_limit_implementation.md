# Unit Limit Implementation Summary

## Changes Made

### 1. Unit Limit Logic in "Add Subjects" Modal
- **Functionality**: Prevents adding subjects if the total units (existing + selected) would exceed the curriculum's total unit limit.
- **Validation**: Checks the limit *before* allowing a subject to be selected.
- **Feedback**: Shows a warning alert if the user tries to exceed the limit.

### 2. UI Updates
- **Modal Footer**: Added a unit counter display: `Units: [Used] used / [Remaining] remaining (Max: [Total])`.
- **Visual Cues**: The unit counter turns red when the limit is reached.

### 3. Technical Details
- **`fetchCurriculums`**: Updated to store `total_units` in the curriculum dropdown options.
- **`showAddSubjectsModal`**: Calculates the current total units of subjects already in the curriculum and retrieves the max limit.
- **`renderModalSubjectList`**: Stores subject units in the checkbox dataset for easy calculation.
- **`updateSelectedCount`**: Calculates the sum of units for selected subjects and updates the footer display.

## How It Works

1.  **Open Modal**: When you click "Add Subjects", the system calculates the total units of all subjects currently in the "Available Subjects" list.
2.  **Select Subject**: When you check a box, the system adds that subject's units to the current total.
3.  **Check Limit**: It compares `Current Total + Selected Total` against the `Curriculum Max Total`.
4.  **Prevent Over-limit**: If the sum exceeds the max, the checkbox is automatically unchecked, and a warning popup appears.
5.  **Display**: The footer updates in real-time to show how many units you have used and how many are remaining.

## Testing
1.  Select a curriculum with a known unit limit (e.g., 72 units).
2.  Open the "Add Subjects" modal.
3.  Try to select subjects until you reach the limit.
4.  Try to select one more subject that would exceed the limit.
5.  Verify that you get a warning and the subject is not selected.
