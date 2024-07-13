<?php

/**
 * PHP version 5
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author   Bui Thanh Cong <buithanhcong.nd@gmail.com>
 * @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class CmetaComponent extends Component
{
    public $cmeta = array(
        'title' => '',
        'description'=>'',
        'keyword'=>''
    );
    
    public $cmeta_robots = 'index, follow';
    
    public function beforeRender(Controller $Controller) 
    {
        $this->Controller = $Controller;
        $this->getMeta();
    }
    
    public function getMeta()
    { 
        if($this->Controller->nodeData['Node']['id'] == 0)
        {
            $this->setMetaHome();
        }
        else if($this->Controller->nodeData['Node']['id'] == -1)
        {
            $this->setMetaCustomized();
        }
        else
        {
            $this->setMeta($this->Controller->cModalName);
        }
        
        foreach($this->cmeta as $k=>$v)
        {
            if($v == '' && isset($this->Controller->settings[$k]))
            {
                $this->cmeta[$k] = "";
            }
        }

        $this->cmeta['description'] = strip_tags($this->cmeta['description']);
        
        $this->Controller->set('title_for_layout', $this->Controller->Capp->getCleanText($this->cmeta['title']));
        $this->Controller->set('description_for_layout', $this->getMetaTag('description', $this->Controller->Capp->getCleanText($this->cmeta['description'])));
        $this->Controller->set('keyword_for_layout', $this->getMetaTag('keywords', $this->Controller->Capp->getCleanText($this->cmeta['keyword'])));
        $this->Controller->set('robots_for_layout', $this->getRobotsTag($this->cmeta_robots));
        
        $this->Controller->set('og_for_layout', $this->SetMetaFacebook());
        $this->Controller->set('canonical_for_layout', $this->setCanonical());
    }


    public function setCanonical()
    {
        if($this->Controller->nodeData['Node']['id'] == 0)
        {
            $href = DOMAIN;
        }        
        elseif($this->Controller->cModalName == 'Category')
        {
            if(is_array($this->Controller->currentCategory) && count($this->Controller->currentCategory) > 0)
            {
                $href = DOMAIN . $this->Controller->currentCategory['Node']['slug'];
            }
        }  
        elseif($this->Controller->cModalName == 'Tags')
        {
            if(is_array($this->Controller->currentCategory) && count($this->Controller->currentCategory) > 0)
            {
                $href = DOMAIN . $this->Controller->currentCategory['Node']['slug'];
            }
        }
        else
        {
            $href = DOMAIN . $this->Controller->nodeData['Node']['slug'] . '.html';
        }
        
        $href = str_replace('/m.', '/', $href);
        
        return '<link rel="canonical" href="'.$href.'" />' . "\n";
    }

    public function SetMetaFacebook()
    {
        $here = trim(Router::url($this->here), '/');
        if(preg_match('/\//', $here))
        {
            $arr = explode('/', $here);
            $here = $arr[0];
        }
        
        $url = DOMAIN . $here;

        $str = '';

        // $str .= '<meta property="og:type" content="article" />' . "\n";
        // if($here == '')
        //     $str .= '<meta property="og:type" content="website" />' . "\n";
        
        $str .= '<meta property="og:site_name" content="'. trim($this->Controller->settings['title']) .'" />' . "\n";
        $str .= '<meta property="og:url" content="'.$url.'" />' . "\n";
        $str .= '<meta property="og:title" content="'.$this->cmeta['title'].'" />' . "\n";
        $str .= '<meta property="og:description" content="'.$this->cmeta['description'].'" />' . "\n";

        if($this->Controller->pageImage != '')
            $str .= '<meta property="og:image" content="'.DOMAIN . $this->Controller->pageImage.'" />' . "\n";
        else 
            $str .= '<meta property="og:image" content="'.DOMAIN . $this->Controller->pageImageLogo.'" />' . "\n";

        // $view = new View($this);
        // $viewdata = $view->render('home_index','home');
        // $viewdata = preg_replace('/\n/', '', $viewdata);
        // echo $viewdata; die;
        
        return $str;
    }

    public function setMetaHome()
    {
        $this->cmeta['title'] = $this->Controller->settings['title'];
        $this->cmeta['description'] = $this->Controller->settings['description'];
        $this->cmeta['keyword'] = $this->Controller->settings['keyword'];
        $this->cmeta_robots = 'index,follow';
    }
    

    public function setMetaCustomized()
    {
        $this->cmeta['title'] = $this->Controller->data['Node']['title'];
        $this->cmeta['description'] = $this->Controller->data['Node']['description'];

        // $this->cmeta['keyword'] = $this->Controller->data[$modelName]['seo_keyword'];

        // $this->cmeta_robots = $this->Controller->data['Node']['robots'] == 0 ? 'noindex,follow' : $this->cmeta_robots;
    }


    public function setMeta($modelName)
    {
        if($modelName == 'Category')
        {
            $this->setMetaCategory();
        }
        else
        {
            $this->cmeta['title'] = $this->Controller->data['Node']['title'];
            $this->cmeta['description'] = $this->Controller->data[$modelName]['seo_description'] != "" ? $this->Controller->data[$modelName]['seo_description'] : $this->Controller->data[$modelName]['description'];
            $this->cmeta['keyword'] = $this->Controller->data[$modelName]['seo_keyword'];
            $this->cmeta_robots = $this->Controller->data['Node']['robots'] == 0 ? 'noindex,follow' : $this->cmeta_robots;
        }
        
        if($this->Controller->cFlagPage == true)
            $this->cmeta_robots = 'noindex, follow';
    }
    
    public function setMetaCategory()
    {
        $this->cmeta['title'] = $this->Controller->nodeData['Node']['title'];
        
        if(is_array($this->Controller->currentCategory) && count($this->Controller->currentCategory) > 0)
        {
            $this->cmeta['title'] = $this->Controller->currentCategory['Category']['seo_title'] ? $this->Controller->currentCategory['Category']['seo_title'] : $this->Controller->currentCategory['Node']['title'];
            $this->cmeta['description'] = $this->Controller->currentCategory['Category']['seo_description'];
            $this->cmeta['keyword'] = $this->Controller->currentCategory['Category']['seo_keyword'];
            $this->cmeta_robots = $this->Controller->currentCategory['Node']['robots'] == 0 ? 'noindex,follow' : $this->cmeta_robots;
        }
    }
    
    public function getMetaTag($name = '', $content='')
    {
        return $content == '' ? '' : '<meta name="' . $name . '" content="'. $content .'" />';
    }

    public function getRobotsTag($status = 'noindex, follow')
    {
        return '<meta name="robots" content="'. $status .'" />';
    }
}