USE html_learn_rpl;

-- Tahap 24: Stitch Syntactic Intelligence UI
-- Tidak menambahkan tabel baru, hanya memastikan brand dan tema UI aktif.

UPDATE ui_settings
SET 
    app_name='SmartLearn',
    logo_icon='terminal',
    primary_color='#3525cd',
    accent_color='#57dffe',
    sidebar_color='#f2f4fc',
    panel_color='#ffffff',
    text_color='#0b1c30',
    heading_color='#3525cd',
    font_family='Inter, Geist, Arial, sans-serif',
    font_size=16,
    radius_size=12,
    theme_template='syntactic_intelligence'
WHERE id_setting=1;
