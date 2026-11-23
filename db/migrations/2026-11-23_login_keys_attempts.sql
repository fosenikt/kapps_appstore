-- Migration: add per-code attempt counter to login_keys (PIN rate-limiting).
--
-- Fresh installs already get this column from db/database.sql. Run this ONCE on an
-- existing production database. Until it is applied, validate_code() falls back to a
-- plain (expiry-checked) match, so login keeps working either way - this migration
-- just turns the rate-limiting on.
--
--   mysql -u <user> -p <database> < db/migrations/2026-11-23_login_keys_attempts.sql
-- or paste into phpMyAdmin.

ALTER TABLE `login_keys`
  ADD COLUMN `attempts` TINYINT NOT NULL DEFAULT 0
  COMMENT 'Failed verification attempts (PIN rate-limiting)' AFTER `hash`;
