<?php

for ($i = 161; $i <= 253; $i++) {
    file_put_contents(
        'query.txt',
        "INSERT INTO aquastore_magento.catalog_category_entity_int (attribute_id, store_id, entity_id, value) VALUES (145, 0, $i, 1);\n",
        FILE_APPEND
    );
}
