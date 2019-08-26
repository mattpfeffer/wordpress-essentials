# Wordpress Essentials

A collection of useful functions and filters to help enforce best practice when working with Wordpress and common third party plugins.

## Features ##

### Security & Hardening ###

- Remove generator metatag to avoid exposing Wordpress version
- Disable XML-RPC access
- Disable REST API access
- Block enumeration of users

### Features ###

- Safely enable support for SVGs in the media library
- Display previews for SVGs in the media library

### Third Party Plugins ###

#### Advanced Custom Fields ####

- Save field group congfiguration under 'fields' folder in _/wp-content_. Allows field configuration to remain theme agnostic and version controlled

#### Gravity Forms ####

- Disable autocomplete on forms for enhanced end-user security

## Usage ##

Designed to be used as a must use plugin, simply place **_wp-essentials.php_** and **_wp-essentials/_** in your _mu-plugins_ folder. This folder can be created uder _/wp-content_ if it doesn't already exist.