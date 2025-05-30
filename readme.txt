=== Project Panorama - Project Management ===

Contributors: bc00per, 3pointross, coopermunc

Tags: project, management, project management, client, dashboard

Requires at least: 5.0

Tested up to: 6.8

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html

Stable tag: 1.6.0

WordPress Project Management, Communication and Client Dashboard Plugin.



== Description ==



= WordPress Project Management and Client Dashboard Plugin =



<strong>With Project Panorama you'll love project management!</strong>



Managing projects is difficult and the wrong tool can actually make it harder, not easier.



* You get lost in lists of tasks

* It's hard to track overall project progress

* You can't efficiently track asset and document reviews and approvals

* Big tasks are hard to quantify, they're either complete or incomplete

* Clients and managers don't have time to process all the information



That's why we built <a href="https://www.projectpanorama.com/">Project Panorama</a>, an easy and intuitive project management platform that gives you, your team, and your clients a clear picture of project progress, tasks, and timing at a glance.



> ...[Project Panorama] transformed the way we run our projects and standardized our process as a web design company.



Panorama was designed to:



✅  Enhance project clarity

✅  Make your client, managers, and team members lives easier

✅  Reduce time spent managing projects

✅  Increase your ability to bill more money

✅  Improve team and client satisfaction

✅  Improve digital communication



Panorama has all the features you need, including:



✅ Create to-do's and track progress

✅ Project assignment and access management

✅ Project milestones

✅ Project timelines

✅ File uploads

✅ File review and approvals

✅ Track project progress

✅ Project phases



= Track Progress =



Project Panorama automatically tracks project progress allowing your clients, managers, and stakeholders to identify exactly how for along the project is at a glance. No more reports, meetings, or emails necessary!



= Track Timing =



Project Panorama automatically calculates project timeline allowing for clear indication of how far along the project is compared to the timeline. Automatically detect if the project is ahead, behind, or on-schedule.



= Project Communication =



Keep project communication in threads and outside of email. All communication can happen through the project portal allowing the entire team to review, respond, and reference.



= Asset Approval and Workflows =



Track the review, revision, and approval workflow of important documents. Clients can approve or reject important documents and assets right within Panorama.



= Client Dashboards =



Each team member or client get's their own custom project dashboard that only gives access to their projects. They can see all projects including progress and timing at a glance.



= Website =

https://www.projectpanorama.com



= Documentation =

https://docs.projectpanorama.com/



= Bug Submission and Support =

https://www.projectpanorama.com/support





== Installation ==



1. Upload 'psp_projects' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Click on the new menu item "Projects" and create your first project

4. Projects have progress bar, phases, milestones, start / end date, documents and descriptions



= Restricting Access =



You can restrict access to projects by making them private or password protected by changing the project visibility in the publish metabox.



= Using Your Own Theme =



Panorama by default has it's own project page theme, this is ensure the best rendering and design across all themes. If you'd rather use your own theme, you can go into Projects > Settings > Appearance and click the "use custom template" option. We recommend using a full width template for best display. Not all themes will render the complex visuals in Panorama equally.



= Shortcodes =



[project_list] - output a list of public projects, options include:



type    -- specify the slug of a project type defined in Projects > Project Types

status  -- 'active' or 'complete' to display completed or active projects

count   -- number to display before pagination

sort    - 'start','end','title'. Sort by start date, end date or alphabetically by title. Default is creation date.



[project_status] - embed a project into a page or post



[project_status_part] - embed a portion of a project into a page or post



[before-phase] [/before-phase] - Show content wrapped in shortcode in a phase description before the phase has started.

[during-phase] [/during-phase] - Show content wrapped in shortcode in a phase description after it has started before it has been completed.

[after-phase] [/after-phase] - Show content wrapped in shortcode in a phase description after the phase has ended.



[before-milestone] [/before-milestone] - Show content wrapped in shortcode before milestone is reached

[after-milestone] [/after-milestone] - Show content wrapped in a shortcode after milestone is reached



== Screenshots ==



1. Indicate overall project progress and identify key milestones

2. Break up projects into individual phases, automatically calculate total project completion by modifying phase completion

3. Provide key project information and automatically track elapsed time

4. Upload key documents and files

5. Get an overview of project progress vs time left before deadline

6. Embed project lists into your site

7. Embed projects into your site, mobile friendly



== Changelog ==

= 1.6.0 =

Plugin rebranded to PSP Projects

Fixed security issues and improved data validation

Improved escaping and sanitization of user inputs

Removed deprecated functions (wp_reset_query() replaced with wp_reset_postdata())

Replaced dynamic string translations with translate() function

Ensured compliance with WordPress plugin security standards

= 1.5.1 =

* Escapes setting outputs for best practice and security



= 1.5 =

* PHP8 compatibility updates



= 1.4.10 =

* Fixes issue with ACF libraries not loading



= 1.4.9 =

* Prevents double function error



= 1.4.8 =

* Updates ACF & CMB2 libraries

* Styling updates

* Better error protection



= 1.4.7 =

* Fixes bug with automatic calculation on tasks with zero progress



= 1.4.6 =

* Fixes nag ignore button



= 1.4.5 =

* Fixes some isset issues

* Adds add project button for quicker / easier management



= 1.4.2 =

* PHP8 compatibility



= 1.4.1 =

* Removes some redundant files

* Styling iterations



= 1.4 =

* Adds ability to update task progress on the front end



= 1.3.6 =

* Fixes undefined constants error

* Design updates



= 1.3.2 =

* Update for WordPress 5.5.3

* Updated CMB2 library

* Updated ACF library



= 1.3.13 =

* WordPress 5.5 optimizations



= 1.3.12 =

* Compatibility fix for plugins that bundle a higher version of chart.js

* Fixes div by zero



= 1.3.11 =

* Fixes issue where automatic calculation is not working with phase shortcodes



= 1.3.10 =

* Updates CMB2 to 2.6



= 1.3.9 =

* Fixes debug notices



= 1.3.8 =

* Fixes issue where project_status_part phases shortcode displays 0% complete



= 1.3.7 =

* project_list shortcode now filters based on projects available to current logged in user

* Fixed issue with WYSIWYG fields initializing without saving



= 1.3.6.1 =

* Minor bug fixes and removing of deprecated files



= 1.3.6 =

* Added support for tasks and automatic calculation by tasks completed

* Limits conditional JS to projects



= 1.3.5 =

* Added support for user assignment / restriction and logins



= 1.3.4 =

* Updated CMB2 library for PHP 7.2 compatibility



= 1.3.3 =

* Code optimization



= 1.3.2 =

* SVN issues, adding missing files



= 1.3 =

* Made month names translatable

* Styling updates

* Updated CMB2 for nicer WYSIWYG on phases



= 1.2.8.1 =

* Fixes typo

* Updates version compatibility



= 1.2.8 =

* Fixes error notice in the admin if WP_DEBUG is enabled



= 1.2.7 =

* Fixes issues with documents not outputting in a embed

* Added shortcodes for before / after milestones

* Added a project calendar



= 1.2.6.5 =

* Checks to see if CMB2 is already loaded

* Updated widget construction method

* Updating front end styling



= 1.2.6.4 =

* Added calendar of start / stop dates



= 1.2.6.3 =

* Bug fixes



= 1.2.6 =



* Added phases and auto calculation to Project Panorama Lite!

* Added options to customize accent colors (phases, timeline, etc...)

* Added repeatable documents interface



= 1.2.6.2 =



* Improved styling for elements in description areas

* Added shortcodes [before-phase] [during-phase] and [after-phase] which display before a phase starts, during an active phase and once a phase is completed

* Date format on backend is determined by user settings

* Added ability to sort by title using the [project_list] shortcode, attribute sort="title"



= 1.2.5.3 =



* Fixed bug with shortcodes



= 1.2.5.2 =



* Document update notification fixes

* Checked for dates before displaying, fixes notices if date isn't set

* Switched last modified time to date on [project_list]

* Added pagination on project listing

* If there isn't a start or end date, hide the time elapsed bar

* [project_list] shortcode will now display a login form if access is set to user and user isn't logged in

* Added logo and home link to project dashboard page



= 1.2.5.2 =

* Fixed bug where timing could be off when using an embed shortcode

* Added a simple project list / archive page for logging in and seeing your list of projects (i.e. /panorama/project-name the login would be /panorama/)

* Added better support for handling wide height ranges between project phases

* Improved the UI of the project heading area

* Added the ability to sort by start or end date with [project_list]

* Fixed bug where if you had a project password protected and restricted to users you couldn't update tasks from the front end



= 1.2.5.1 =

* Separated jQuery from frontend lib file

* Reworking of how and when admin scripts are enqueued for compatibility

* Added Advanced tab for debugging

* Switched dashboard widget chart to chart.js

* Renamed comments.php to psp-comments.php for compatibility

* Core fixes



= 1.2.5 =

* Added front end updating of tasks

* Added front end updating of documents

* Added notification system for document updates

* New project page interface

* Added time elapsed feature, tracks overall time elapsed compared to project completion

* Improved project listing interface on the backend

* Improved project listing shortcode display

* Split project templates into sub parts for easier customization

* Reworked file structure

* Misc bug fixes and improvements

* Split field loading into individual parts, function to check if field files exist in theme directory for customization

* BETA FEATURE: Load Panorama into your theme templates



= 1.2.2.2 =

* Only enqueue javascript files on pages that need them for compatibility

* Improved formatting of e-mail notifications on smaller screens

* Added password reset link to Panorama login

* Removed dashboard widget for users who are not editor level or higher

* Fixed issue where some users can't set a default e-mail / from name for notifications



= 1.2.2.1 =

* Fixed calculation bug with shortcodes

* Fixed weighting issue with previously completed projects

* Switched method of designated completed projects to custom taxonomy

* Fixed conflicts with ACF5 users and progress bars



= 1.2.2 =

* Added e-mail notifications

* Broke settings into three tabs

* Cleaned up admin interface

* Added ability to expand and collapse phases in admin (Thanks Mark Root-Wiley http://mrwweb.com/)

* Added graph to dashboard widget

* Reworked phase weighting, you can now specify hours instead of percentage

* Phases now have project specific settings rather than each individual phase

* Added setting to expand tasks by default

* Fixed unset variable PHP notice

* You can now specify number of projects to display in the [project_list] shortcode





= 1.2.1.8.2 =

* Added the ability to use your own template, simply create a folder called "panorama" in your theme directory and then copy /wp-content/plugins/panorama/lib/templates/single.php into it. You can then modify the file as you'd like

* Added project listing widget

* You can now use URLs for documents

* Added color customizations and an open css text box to the settings page

* Fixed bug with DISQUS plugins



= 1.2.1.8.1 =

* Minor bug fix



= 1.2.1.8 =

* Adjusted project_list shortcode to only display projects viewing user has access to, this can be overwritten by adding an access="all" attribute

* Added two user roles, 'Project Owner' and 'Project Manager' - More information here http://www.projectpanorama.com/docs/permissions

* Project editing in the admin is now restricted by the access control settings, i.e. authors/editors/project owners can only edit projects assigned to them (admins and project managers can edit all projects)

* Fixed issue where auto-calculation wouldn't work if you only had one task



= 1.2.1.6 =

* Added function to translate ACF fields



= 1.2.1.5 =

* Fixed output of "Fired" on plugin page

* Added [panorama_dashboard] shortcode

* Expanding and collapsing task lists

* Fixed issue where project list wouldn't output completed only projects

* Slightly redesigned interface



= 1.2.1.2 =

* Working translation and textdomain

* Added translations for French and Bulgarian - Thanks Gregory Further and Yassen Yotov!

* Move settings into the Project Panorama menu

* Added hooks into the template for future addons and easier styling adjustments

* Login form no longer trips security on WPEngine

* Fixed some misc bugs

* Adds dashboard widget



= 1.2.1 =

* Better translation and textdomain support

* Reworked shortcode system, now you can embed parts of projects, configure your project output and adjust what projects are listed

* Added "Project Type" custom taxonomy

* Added the ability to alter your project slug (from panorama to anything else)

* Added the ability to brand your projects

* Styling improvements and fixes

* Expanded WYSIWYG tools

* Support for WP 3.9



= 1.2 =

* Swapped out donut charts for Pizza Charts by Zurb (much nicer at all resoultions, better IE support)

* Added password protection

* Added user management / restrictions

* Check for duplicate post plugin before including

* Added option to noindex projects

* Minor styling tweaks

* Only load scripts and styles when a shortcode is used or on a project page



= 1.1.3 =

Small Bug Fixes - Added icons to new shortcode buttons



= 1.0 =

* Initial Release!

