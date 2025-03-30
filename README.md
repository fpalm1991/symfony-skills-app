# Symfony Skills App Prototype (Symfony + Stimulus)

This is a learning prototype built with **Symfony** and **Stimulus.js**, focused on updating user skill data interactively and modeling custom relationships in the database.

## About The Project

- Updating the database using **Stimulus.js + Fetch API**
- Secure AJAX requests with **CSRF protection**
- Dynamic skill selection interface with checkboxes
- A custom pivot table `UserSkillLevel` to map:
    - A **User**
    - A **Skill**
    - And their **Skill Level**

## Database Design 

One of the interesting aspects of this project was designing a normalized structure for skill tracking:

```text
User <-> Skill <-> SkillLevel
