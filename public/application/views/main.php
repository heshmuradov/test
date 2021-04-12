<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:36
 * To change this template use File | Settings | File Templates.
 */

?>
<style>
        /*@import "/css/new/jquery-ui-1.8.21.custom.css"; */
    @import "/css/new/jquery-ui-1.8.21.custom.css";
    @import "/css/ui_jqgrid.css";
</style>
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.21.custom.min.js"></script>

<body>
<div id="asosiy">
    <?php $this->load->view("menu")?>
    <div id="vback">
        <style>
            body {
                font-size: 62.5%;
            }

            fieldset {
                padding: 0;
                border: 0; /*margin-top: 0px;*/
            }

            div#users-contain {
                width: 350px;
                margin: 20px 0;
            }

            div#users-contain table {
                margin: 1em 0;
                border-collapse: collapse;
                width: 100%;
            }

            div#users-contain table td, div#users-contain table th {
                border: 1px solid #eee;
                padding: .6em 10px;
                text-align: left;
            }

            .ui-dialog .ui-state-error {
                padding: .3em;
            }

            .validateTips {
                border: 1px solid transparent;
                padding: 0.3em;
            }
        </style>
        <center><img src="/temp/icons/tmek.png" alt=""/></center>
    </div>
</div>
</body>
</html>
