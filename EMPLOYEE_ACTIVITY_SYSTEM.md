# Employee Activity Log & Status Management System

## Overview
Complete employee monitoring system for super admins and admins to track employee activities, manage their status (active/inactive), and monitor export activities with detailed timestamps.

## Features Implemented

### 1. Database Structure

#### Employee Activity Logs Table
- **Table**: `employee_activity_logs`
- **Fields**:
  - `id` - Primary key
  - `user_id` - Foreign key to users table
  - `activity_type` - Type of activity (export, login, logout, view)
  - `description` - Human-readable description
  - `metadata` - JSON field for additional data
  - `ip_address` - User's IP address
  - `user_agent` - Browser information
  - `created_at` / `updated_at` - Timestamps

#### User Status Management
- **Added Fields to Users Table**:
  - `status` - ENUM('active', 'inactive') with default 'active'
  - `last_activity` - Timestamp of last user activity

### 2. Models & Services

#### EmployeeActivityLog Model
- Relationships with User model
- Scopes for filtering (by type, date range, recent activities)
- Formatted descriptions and activity icons
- Color coding for different activity types

#### ActivityLogService
- Centralized logging service
- Methods for different activity types:
  - `logExport()` - Log export activities
  - `logLogin()` - Log user login
  - `logLogout()` - Log user logout  
  - `logPageView()` - Log page visits
- Statistics generation
- Browser and platform detection

#### Updated User Model
- Activity log relationships
- Status management methods
- Helper methods for status badges
- Last activity tracking

### 3. Controller Enhancements

#### EmployeeController
- **New Methods**:
  - `activityLogs($id)` - View individual employee activities
  - `toggleStatus($id)` - Activate/deactivate employees
  - `allActivities()` - View all employee activities
  - `exportActivities()` - Export activity reports as CSV

#### CurriculumExportToolController
- Automatic activity logging for exports
- Page view tracking
- Last activity updates

#### AuthController
- Login/logout activity logging
- Employee-specific tracking

### 4. User Interface

#### Enhanced Employee Management
- **Status Column**: Visual badges (Active/Inactive)
- **Activity Count**: Number of activities per employee
- **Last Activity**: Human-readable timestamps
- **Quick Actions**: 
  - Toggle status (Activate/Deactivate)
  - View activity logs
  - Edit employee details

#### Activity Statistics Dashboard
- **Total Activities**: Overall activity count
- **Today's Activities**: Current day statistics
- **Weekly Activities**: 7-day activity count
- **Export Count**: Total exports performed

#### Individual Employee Activity Logs
- **Timeline View**: Chronological activity display
- **Activity Types**: Icons and colors for different activities
- **Detailed Metadata**: Expandable details for each activity
- **Browser Information**: Platform and browser detection
- **IP Tracking**: Security monitoring

#### All Activities View
- **Consolidated Timeline**: All employee activities
- **Employee Filtering**: View activities by specific employee
- **Export Functionality**: CSV export with date range
- **Activity Type Legend**: Visual guide for activity types

### 5. Routes Added

```php
// Employee Activity Management
Route::get('/employees/{id}/activity-logs', [EmployeeController::class, 'activityLogs']);
Route::patch('/employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus']);
Route::get('/employee-activities', [EmployeeController::class, 'allActivities']);
Route::get('/employee-activities/export', [EmployeeController::class, 'exportActivities']);
```

### 6. Activity Types Tracked

#### 🔑 Login Activities
- Timestamp of login
- IP address and browser information
- Platform detection (Windows, Mac, Linux, etc.)

#### 🚪 Logout Activities  
- Logout timestamp
- Session duration tracking

#### 📄 Export Activities
- Export type (curriculum, subject, etc.)
- File name and format
- Curriculum/subject details
- Export timestamp

#### 👁️ Page View Activities
- Page accessed
- URL and HTTP method
- Access timestamp

#### 🔄 Status Change Activities
- Status changes (active ↔ inactive)
- Who made the change
- Timestamp of change

### 7. Security Features

#### Access Control
- **Admin Only**: All activity monitoring features
- **Employee Restrictions**: Cannot view their own or others' logs
- **Route Protection**: Middleware-protected endpoints

#### Data Privacy
- **IP Tracking**: For security monitoring
- **Browser Fingerprinting**: Device identification
- **Session Tracking**: Login duration monitoring

### 8. Export & Reporting

#### CSV Export Features
- **Date Range Selection**: Custom date filtering
- **Complete Activity Data**: All relevant fields
- **Employee Information**: Name, username, email
- **Activity Details**: Type, description, timestamp
- **Technical Data**: IP, browser, platform

#### Export Columns
1. Employee Name
2. Username  
3. Activity Type
4. Description
5. Date & Time
6. IP Address
7. Browser Information

### 9. Visual Design

#### Status Indicators
- **🟢 Active**: Green badge with "Active" text
- **🔴 Inactive**: Red badge with "Inactive" text

#### Activity Icons
- **📄 Export**: Blue color scheme
- **🔑 Login**: Green color scheme  
- **🚪 Logout**: Gray color scheme
- **👁️ View**: Purple color scheme
- **⬇️ Download**: Indigo color scheme

#### Statistics Cards
- **Modern Design**: Clean card layout
- **Color Coding**: Different colors for each metric
- **Icons**: Visual representation of each statistic

### 10. Usage Instructions

#### For Super Admins & Admins

1. **View Employee Status**:
   - Go to Employee Management
   - See status badges in the table
   - Use toggle buttons to activate/deactivate

2. **Monitor Activities**:
   - Click "View Logs" next to any employee
   - Use "View All Activities" for system-wide view
   - Export reports using date range filters

3. **Track Exports**:
   - Monitor when employees export data
   - See what they exported and when
   - Track export frequency and patterns

#### For System Monitoring

1. **Security Monitoring**:
   - Track login patterns and locations
   - Monitor unusual activity times
   - Identify potential security issues

2. **Usage Analytics**:
   - See which employees are most active
   - Track export usage patterns
   - Monitor system engagement

3. **Compliance Reporting**:
   - Generate activity reports for audits
   - Track data access and exports
   - Maintain activity logs for compliance

## Benefits

### 🔒 Enhanced Security
- Complete audit trail of employee activities
- IP and browser tracking for security
- Login/logout monitoring

### 📊 Better Management
- Real-time employee status management
- Activity statistics and insights
- Easy activation/deactivation of accounts

### 📈 Improved Monitoring  
- Detailed export tracking
- Usage pattern analysis
- System engagement metrics

### 📋 Compliance Ready
- Complete activity logs
- Exportable reports
- Audit trail maintenance

### 🎯 User-Friendly Interface
- Intuitive status management
- Visual activity indicators
- Easy-to-use reporting tools

This comprehensive system provides administrators with complete visibility into employee activities while maintaining security and compliance standards.
