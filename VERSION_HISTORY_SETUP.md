# Curriculum Version History System Setup Guide

## Overview
This system provides comprehensive version tracking for curriculum mappings, allowing users to view historical changes, compare versions, and track modifications over time.

## Features
- 📊 **Version History Tracking** - Automatic snapshots when curriculum changes
- 🕒 **Timeline View** - See all versions with timestamps and descriptions
- 👤 **User Attribution** - Track who made each change
- 🔍 **Detailed Views** - View complete curriculum state for any version
- 🎨 **Beautiful UI** - Integrated seamlessly with existing design system

## Setup Instructions

### 1. Run Database Migration
```bash
php artisan migrate --path=database/migrations/2024_01_01_000000_create_curriculum_histories_table.php
```

### 2. Seed Sample Data (Optional)
```bash
php artisan db:seed --class=Database\\Seeders\\CurriculumHistorySeeder
```

### 3. Alternative: Use Setup Command
```bash
# Setup migration only
php artisan curriculum:setup-version-history

# Setup with sample data
php artisan curriculum:setup-version-history --seed
```

## How It Works

### Automatic Version Creation
The system automatically creates version snapshots when:
- Curriculum subjects are updated via subject mapping
- Individual subjects are removed from curriculum
- Manual snapshots are created via API

### Version Data Structure
Each version contains:
- Complete curriculum information
- All mapped subjects with details
- Metadata (total subjects, units, distribution)
- Change description and timestamp
- User who made the change

### Frontend Integration
- **Clock Icon**: Appears on curriculum cards on hover
- **Version List Modal**: Shows all versions with "Current" badges
- **Version Details Modal**: Complete curriculum state view
- **Loading States**: Smooth user experience with spinners
- **Error Handling**: User-friendly error messages

## API Endpoints

### Get Version List
```
GET /api/curriculum-history/{curriculumId}/versions
```
Returns all versions for a curriculum with metadata.

### Get Version Details
```
GET /api/curriculum-history/{curriculumId}/versions/{versionId}
```
Returns complete curriculum state for a specific version.

### Create Manual Snapshot
```
POST /api/curriculum-history/{curriculumId}/snapshot
Body: { "change_description": "Manual backup before major changes" }
```

### Compare Versions (Future Feature)
```
GET /api/curriculum-history/{curriculumId}/compare/{version1Id}/{version2Id}
```

## File Structure

### Backend Files
- `app/Models/CurriculumHistory.php` - Main model
- `app/Http/Controllers/CurriculumHistoryController.php` - API controller
- `app/Services/CurriculumVersionService.php` - Version management service
- `database/migrations/2024_01_01_000000_create_curriculum_histories_table.php` - Database schema
- `database/seeders/CurriculumHistorySeeder.php` - Sample data seeder
- `app/Console/Commands/SetupVersionHistory.php` - Setup command

### Frontend Files
- `resources/views/subject_mapping_history.blade.php` - Updated with version history UI
- `routes/web.php` - API routes added

### Integration Points
- `app/Http/Controllers/CurriculumController.php` - Auto-snapshot on changes

## Usage Guide

### For Users
1. **View Version History**:
   - Go to Subject Mapping History page
   - Hover over any curriculum card
   - Click the clock icon that appears

2. **Browse Versions**:
   - See all versions with timestamps
   - Current version is highlighted with green badge
   - Click "View Details" to see complete curriculum state

3. **Version Details**:
   - View all subjects organized by year/semester
   - See version information and change description
   - Color-coded subject types for easy identification

### For Developers
1. **Manual Snapshots**:
   ```php
   use App\Services\CurriculumVersionService;
   
   CurriculumVersionService::createSnapshotOnUpdate(
       $curriculumId, 
       'Custom change description'
   );
   ```

2. **Automatic Integration**:
   The system is already integrated with:
   - `CurriculumController::saveSubjects()` - Auto-snapshot on save
   - `CurriculumController::removeSubject()` - Auto-snapshot on remove

3. **Cleanup Old Versions**:
   ```php
   CurriculumVersionService::cleanupOldVersions($curriculumId, 10); // Keep last 10 versions
   ```

## Database Schema

### curriculum_histories Table
- `id` - Primary key
- `curriculum_id` - Foreign key to curriculums table
- `version_number` - Sequential version number
- `snapshot_data` - JSON data containing complete curriculum state
- `change_description` - Description of what changed
- `changed_by` - Foreign key to users table
- `created_at` - Timestamp of version creation
- `updated_at` - Updated timestamp

### Indexes
- `curriculum_id, version_number` - For efficient version queries
- `created_at` - For chronological sorting

## Performance Considerations

### Storage
- JSON snapshots are compressed efficiently by MySQL
- Old versions can be cleaned up automatically
- Indexes ensure fast queries even with many versions

### Memory
- Version details are loaded on-demand
- Large curriculum data is paginated in UI
- Efficient caching can be added if needed

## Troubleshooting

### Common Issues
1. **Migration Fails**: Ensure database connection is working
2. **No Versions Showing**: Check if curriculum has any mapped subjects
3. **API Errors**: Verify routes are properly registered
4. **UI Not Loading**: Check browser console for JavaScript errors

### Debug Commands
```bash
# Check if table exists
php artisan tinker
>>> Schema::hasTable('curriculum_histories')

# Check version count
>>> App\Models\CurriculumHistory::count()

# Test API endpoint
curl http://localhost/api/curriculum-history/1/versions
```

## Future Enhancements

### Planned Features
- **Version Comparison**: Side-by-side diff view
- **Restore Functionality**: Rollback to previous versions
- **Export History**: PDF reports of version changes
- **Bulk Operations**: Mass version management
- **Advanced Filtering**: Search versions by date, user, or changes

### Integration Opportunities
- **Audit Logging**: Enhanced change tracking
- **Notifications**: Alert users of curriculum changes
- **Approval Workflow**: Version approval before activation
- **Backup Integration**: Automated backups of critical versions

## Support

For issues or questions:
1. Check this documentation first
2. Review error logs in `storage/logs/laravel.log`
3. Test API endpoints directly
4. Verify database schema and data

The version history system is designed to be robust and user-friendly, providing valuable insights into curriculum evolution over time.
