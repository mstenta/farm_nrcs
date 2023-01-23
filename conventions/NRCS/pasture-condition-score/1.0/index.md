# Pasture Condition Score

**Version 1.0**

```yml
convention_id: "NRCS:pasture-condition-score:1.0"
dependencies:
  - farm:farm_observation
```

## Purpose

The NRCS provides a standardized process for measuring pasture condition based
on 10 indicators with scores from 1-5. These 10 scores are summed to determine
the overall pasture condition score.

## Specification

This specification is based on the NRCS Pasture Condition Score Sheet dated
2/7/2020:

https://www.nrcs.usda.gov/sites/default/files/2022-09/Pasture%20Condition%20Score%20Sheet.pdf

Pasture condition scores MUST be recorded as an "Observation" (`observation`)
log.

The "Timestamp" (`timestamp`) field of the log MUST be the date when the
pasture was observed.

The "Assets" (`asset`) field of the log SHOULD reference an asset that
represents the pasture being observed.

All indicator scores, as well as the overall pasture condition score MUST be
recorded as quantities referenced by the log's "Quantities" (`quantity`) field.
The quantity type SHOULD be "Standard" (`standard`). The quantity "Measure"
(`measure`) MUST be "Rating" (`rating`). The quantity "Value" (`value`) for the
overall score MUST be a whole number between 1 and 50. The quantity "Value"
(`value`) for each indicator score MUST be a whole number between 1 and 5. The
quantity "Label" (`label`) MUST be one of the "Allowed quantity labels" listed
below. The quantity "Units" (`units`) MUST be left blank.

**Allowed quantity labels**

- Overall Score
- Percent Desirable Plants
- Percent Legume by Dry Weight
- Live (includes dormant) Plant Cover
- Plant Diversity by Dry Weight
- Plant Residue and Litter as Soil Cover
- Grazing Utilization and Severity
- Livestock Concentration Areas
- Soil Compaction and Soil Regenerative Features
- Plant Vigor
- Erosion

## Notable implementations

The Pasture Condition Score quick form provided by the farmOS NRCS module
creates observation logs that follow this convention.
