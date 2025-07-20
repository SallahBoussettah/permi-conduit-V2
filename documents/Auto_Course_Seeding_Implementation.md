# Auto Course Seeding Implementation Plan

## Overview
This document outlines the implementation plan for automatically seeding standardized courses when a super admin creates a new school, while maintaining flexibility for individual school customization.

## Current System vs New System

### Current System
- Inspectors/Admins manually create courses for their schools
- No standardized course structure across schools
- Each school starts with empty course catalog
- **Permit categories system already exists** (C, CE, D, etc.)
- **Candidates already see courses based on their permit category**

### New System
- **Standardized courses** automatically seeded when school is created
- **Courses linked to existing permit categories** (C, CE, D, etc.)
- **Fixed course structure** based on Plateau exam sections
- **Optional individual courses** can still be created by school admins/inspectors
- **Existing permit category filtering continues to work**

## Course Structure to be Auto-Seeded

### Fixed Courses (Auto-seeded for every school):
1. **Interrogation Écrite (QCM)**
   - Content: QCM practice and preparation materials
   - Points: 3 pts max

2. **Socle Minimum 1**
   - Content: Pre-driving checks (immobilization, lights, documents, cabin)
   - Points: 7 pts max

3. **Thème**
   - Content: 6 oral technical topics (randomized during exam)
   - Points: 3 pts max

4. **Interrogation Orale (I.O.)**
   - Content: Safety and theoretical knowledge
   - Points: 3 pts max

5. **Socle Minimum 2**
   - Content: Post-theme checks (braking, engine, posture)
   - Points: 4 pts max

6. **Manœuvres**
   - Content: Practical maneuvers (reversing, turning, parking)
   - Points: 1 pt (BON/ECHEC)

## Permit Categories System (Already Exists)

### Super Admin Level
- **Creates global permit categories** (C, CE, D, etc.) - **ALREADY IMPLEMENTED**
- These permit categories apply to all schools
- Examples:
  - "Permis C" (Category C trucks)
  - "Permis CE" (Category CE truck + trailer)
  - "Permis D" (Category D buses)

### Course-Permit Category Linking
- **Auto-seeded courses** are linked to existing permit categories (C, CE, D, etc.)
- **Individual courses** can still be created and linked to permit categories
- **Candidates** see courses based on their assigned permit category - **ALREADY WORKING**

## Database Changes Required

### 1. Standard Courses Template Table (New)
```sql
CREATE TABLE standard_course_templates (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    permit_category_id BIGINT, -- Links to existing permit_categories table
    sequence_order INTEGER,
    exam_section VARCHAR(100), -- IE, Socle1, Theme, IO, Socle2, Manoeuvres
    default_materials JSON, -- Template materials to be created
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (permit_category_id) REFERENCES permit_categories(id)
);
```

### 2. No Course Categories Changes Needed
- We use the existing `permit_categories` table (C, CE, D, etc.)
- We use the existing `courses.permit_category_id` relationship
- **No new course category system needed**

## Implementation Steps

### Phase 1: Database Structure
1. Create new migration for `standard_course_templates` table
2. Create seeders for standard course templates (linked to existing permit categories)
3. No changes needed to existing permit categories system

### Phase 2: Super Admin Interface
1. **Standard Course Templates Management**
   - Interface to manage the 6 standard courses (IE, Socle1, Theme, IO, Socle2, Manoeuvres)
   - Link each standard course to existing permit categories (C, CE, D, etc.)
   - Define default materials for each course (optional)

### Phase 3: School Creation Enhancement
1. **Auto-seeding Logic**
   - When super admin creates a school:
     - Create 6 standard courses for the school (based on templates)
     - Link courses to existing permit categories
     - Create basic course materials (optional)

2. **School Creation Workflow**
   ```
   Super Admin Creates School
   ↓
   System automatically creates:
   - 6 Standard Courses (IE, Socle1, Theme, IO, Socle2, Manoeuvres)
   - Links courses to existing permit categories (C, CE, D, etc.)
   - School is ready with complete course structure
   ```

### Phase 4: Maintain Existing Functionality
1. **School Admin/Inspector Course Creation**
   - Keep existing CRUD functionality for individual courses
   - Allow assignment to existing permit categories
   - Individual courses work alongside auto-seeded courses

2. **Candidate Experience**
   - Candidates see both auto-seeded and individual courses
   - Progress tracking works for all course types
   - **Existing permit category filtering continues to work as before**

## User Roles and Permissions

### Super Admin
- ✅ Create/manage standard course templates
- ✅ Create schools (triggers auto-seeding)
- ✅ View all schools and their courses
- ✅ Manage global permit categories (already exists)

### School Admin
- ✅ View auto-seeded courses (read-only for structure)
- ✅ Add materials to auto-seeded courses
- ✅ Create additional individual courses
- ✅ Assign permit categories to candidates (already exists)

### Inspector
- ✅ View auto-seeded courses
- ✅ Add materials to courses
- ✅ Create additional individual courses (if permitted)
- ✅ Manage course materials

### Candidate
- ✅ Access all courses based on their permit category
- ✅ See standardized learning path (IE → Socle1 → Theme → IO → Socle2 → Manoeuvres)
- ✅ Access additional individual courses

## Benefits of This Approach

### 1. Standardization
- Every school starts with the same core curriculum
- Consistent learning experience across all schools
- Aligned with official Plateau exam structure

### 2. Flexibility
- Schools can still customize with additional courses
- Materials can be added to standard courses
- Individual school needs are accommodated

### 3. Efficiency
- No manual setup required for new schools
- Immediate course availability
- Reduced administrative overhead

### 4. Scalability
- Easy to add new schools
- Consistent structure for future enhancements
- Simplified maintenance

## Migration Strategy

### For Existing Schools
1. **Backward Compatibility**
   - Existing courses remain unchanged
   - Existing functionality continues to work
   - Optional migration tool to standardize existing schools

2. **Gradual Adoption**
   - Super admin can choose to "standardize" existing schools
   - Migration script to create missing standard courses
   - Preserve existing custom courses

### For New Schools
- Automatic seeding from day one
- Complete course structure immediately available
- Ready for candidate enrollment

## Technical Considerations

### 1. Seeding Performance
- Use database transactions for school creation
- Batch insert operations for efficiency
- Background job processing for large schools

### 2. Data Integrity
- Foreign key constraints
- Validation rules for course templates
- Rollback mechanisms for failed seeding

### 3. Customization Limits
- Standard courses: structure fixed, materials customizable
- Individual courses: full customization allowed
- Permit category assignments: use existing permit categories (C, CE, D, etc.)

## Next Steps

1. **Review and Approval** of this implementation plan
2. **Database Design** finalization
3. **Migration Scripts** creation
4. **Super Admin Interface** development
5. **Auto-seeding Logic** implementation
6. **Testing** with sample schools
7. **Deployment** and existing school migration

## Questions for Clarification

1. Should existing schools be automatically migrated to this new structure?
2. Can school admins modify the titles/descriptions of auto-seeded courses?
3. Should there be default materials seeded with the courses, or just empty course shells?
4. Which permit categories should each of the 6 standard courses be linked to? (All to Category C, or different ones?)
5. Should the auto-seeded courses be marked as "standard" vs "custom" in the database?

---

This implementation maintains the flexibility of the current system while providing the standardization and efficiency benefits of auto-seeded courses. The approach ensures that every school has a complete, exam-aligned curriculum from day one while still allowing for customization and individual course creation.