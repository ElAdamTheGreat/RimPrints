<?php
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