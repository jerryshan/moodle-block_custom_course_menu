<?php

function xmldb_block_my_courses_upgrade($oldversion) {
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    if ($oldversion < 2013050314) {
        // Define index usercat (unique) to be added to block_my_courses.
        $table = new xmldb_table('block_my_courses');
        $index = new xmldb_index('usercat', XMLDB_INDEX_UNIQUE, array('userid', 'categoryid'));

        // Conditionally launch add index usercat.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define table block_my_courses_meta to be created.
        $table = new xmldb_table('block_my_courses_meta');

        // Adding fields to table block_my_courses_meta.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('item', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, 'category');
        $table->add_field('itemid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('hide', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table block_my_courses_meta.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table block_my_courses_meta.
        $table->add_index('useiteitemid', XMLDB_INDEX_UNIQUE, array('userid', 'item', 'itemid'));
        $table->add_index('useritem', XMLDB_INDEX_NOTUNIQUE, array('userid', 'item'));

        // Conditionally launch create table for block_my_courses_meta.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // My_courses savepoint reached.
        upgrade_block_savepoint(true, 2013050314, 'my_courses');
    } else if ($oldversion < 2013050814) {
        // My_courses savepoint reached.
        upgrade_block_savepoint(true, 2013050814, 'my_courses');
    }
}