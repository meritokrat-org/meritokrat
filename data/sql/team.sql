SELECT
  COUNT(NULLIF(category = 4, FALSE))                         AS rgn,
  COUNT(NULLIF(category = 3 AND region_id = :region, FALSE)) AS cit,
  COUNT(NULLIF(category = 2 AND region_id = :region, FALSE)) AS dsc,
  COUNT(NULLIF(category = 1 AND region_id = :region AND district_id = :district, FALSE)) AS prm
FROM team
WHERE active = 1;