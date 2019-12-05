# Project Events

This project is made in Laravel 5.8.35 and is a bundling of multiple concepts for reviewing and training purposes e.g.:

-   Basic model relations
-   Axios GET and POST calls
-   Recurrence Rules
-   Vue components

## Description

Site visitors see a list of Projects with related Events. When logged in, you can add or edit Projects of add Events. Those can be related to existing projects, but can also exist on their own.

Events can be planned on a specific date, but also on a recurring pattern (RRule). Those patterns are then interpreted and the first upcoming one is shown in the Project list, seen by visitors

## Sidenotes

This is an ongoing project, started by implementing recurrence rules to an event and built outwards from there. Therefore this is not directly a commercially viable product.

(The current database seeds are for testing purposes only and are completely made up.)

## Planned Features

-   Better Calendar integration:
    -   Project related Event background color
    -   Event information on hover/click

*   Frontend improvements

    -   Lazy loading projects
    -   Calendar on frontend
    -   Project filters

-   Backend overview
    -   Simpler list of projects and related events
    -   Event editing

<img src="http://companiek.com/projectevents/scrn02.JPG" />
