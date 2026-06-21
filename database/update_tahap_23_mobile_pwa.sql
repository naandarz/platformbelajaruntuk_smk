USE html_learn_rpl;

-- Tahap 23 tidak menambahkan tabel baru.
-- File ini hanya memastikan identitas UI tetap SmartLearn jika diperlukan.

UPDATE ui_settings
SET 
    app_name='SmartLearn',
    logo_icon='SL',
    theme_template='smartlearn_clean'
WHERE id_setting=1;
