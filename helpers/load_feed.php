<?php

defined('C5_EXECUTE') or die("Access Denied.");

class LoadFeedHelper {
    private $error = false;
    private $html;

    /**
    * Loads and then outputs via JSONP either a custom HTML generating script or a named
    * C5 stack.
    * @param $type - either 'custom' or 'stack'
    * @param $name - in the case of a stack, the exact name of the stack.  in the case of a
    *               custom script, it will be the filename of the file in the /tools/feed/custom directory
    * @return string - Json encoded array of HTML output
    */
    public function getFeed($type, $name){
        if ($type == 'custom') {
            $this->getCustomFeed($name);
        } elseif ($type == 'stack') {
            $this->getStack($name);
        } else {
            $this->html = '<p class="error"> Expected feed type of custom or stack and found ' . $type . '</p>';
            $this->error = true;
        }

        return $this->createJson();

    }

    /**
    *Captures the output of the given custom script and assigns ito the $html property
    * @param string $name - the filename of the custom script that constains the output
    * @return void
    */
    private function getCustomFeed($name) {
        //start capturing the output stream that the custom feed will generate
        ob_start();

        //result is null if the include cannot be found
        $result = include  getcwd() . '\tools\feed\custom\\'. $name . '.php';
        if ($result) {
            //stop capturing the output stream and assign everything caught to a variable
            $this->html = ob_get_clean();
        } else {
            $this->html = '<p class="error"> Unknown feed name: ' . $name . '</p>';
            $this->error = true;
        }
    }

    /**
    * Captures the output of the given stack and assigns it to the $html property
    * @param string $name - the name of the stack to be captured
    * @return void
    */
    private function getStack($name) {
        $stack = Stack::getByName($name);
        if ($stack) {
            $blocks = $stack->getBlocks();
            //start capturing output stream
            ob_start();
            foreach ($blocks as $block) {
                $block->display();
            }
            //assign all captured output to variable
            $this->html = ob_get_clean();
        }  else {
            $this->html = '<p class="error"> Unknown stack name: ' . $name . '</p>';
            $this->error = true;
        }
    }

    /**
    * Creates a JSON string from the data that has been collected so far in the $html property
    * @return string $json - JSON encoded object indicating succcess and output message format:
    *   {
    *   "status" : "Success",
    *   "data"  :  "<p>Json encoded HTML here<\/p>"
    *    }
    */
    private function createJson(){
        //keep the images from loading when first brought into the browsers
        $this->html = str_replace('src="', 'src-data="', $this->html);

        $returnData = [];
        if ($this->error) {
            $returnData['status'] = 'Error';
        } else {
            $returnData['status'] = 'Success';
        }

        $returnData['data'] = $this->html;
        $json = json_encode($returnData);
        return $json;
    }
}
