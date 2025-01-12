<?php
/**
 * This file is the loader component. It is used to show a loader on the screen when a client is fetching some data from the server.
 * @author Adam Gombos
 */

/**
 * @param string $size The size of the loader. Can be 'small' or null.
 * @return void Returns void, but echoes the loader component.
 */
function loader($size = null) {
    echo '<link rel="stylesheet" href="components/loader/loader.css">';
    if ($size === 'small') {
        echo '<style>
                .loader {
                    width: 16px;
                    height: 16px;
                    border: 4px solid transparent;
                    border-top: 4px solid white;
                }
              </style>';
    }
    include 'components/loader/loader.html';
}
?>