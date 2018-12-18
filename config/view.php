<?php
    return [
        "smarty"=>[
            "file"=>CORE_DIR."/lib/smarty/Smarty.class.php",
            "class_name"=>"Smarty",
            "params"=>[
                "left_delimiter"=>"{",
                "right_delimiter"=>"}",
                "cache_dir"=>ROOT."/cache",
                "compile_dir"=>"template_c",
            ],
			"static"=>"/static"
			
        ]
        
    ];
