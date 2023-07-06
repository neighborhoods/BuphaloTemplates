# BuphaloTemplates
A collection of versioned, re-usable templates, annotation processors, and utility classes for Buphalo. 

## Oragnization
Because code is constantly evolving, supporting many different versions of templates may be necessary at a single time.
To facilitate this, this repo is heavily versioned by PHP Version and Internval Version.

Each every object should be stored in the following pattern:

- Top Level Directory (`src/`, `fab/`, or `templates/`)
  - PHP Version (_e.g._ `Php81/` for things that work in PHP 8.1+)
    - 1+ Levels of Object Group (_e.g._ `ClassDefinition/`, `AnnotationProcessors/Prefab8/`)
      - Internal Version (_e.g._ `V1/`)
        - Individual Files
