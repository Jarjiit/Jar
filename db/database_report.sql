USE djamil_gases;

CREATE OR REPLACE VIEW `available_vendor` AS
  SELECT 
    kp.id,
    kp.uuid,
    kp.name,
    kp.position,
    kp.company_name,
    kp.address,
    kp.mobileno,
    kp.email,
    kp.bank_acc,
    kp.is_active,
    kp.jenis_usaha_id,
    ru.name AS jenis_usaha,
    kp.bank_id,
    rb.name AS bank
  FROM kontrak_penyedia kp
  JOIN ref_jenis_usaha ru ON kp.jenis_usaha_id = ru.id
  JOIN ref_bank rb ON kp.bank_id = rb.id
  WHERE kp.is_active = 1;