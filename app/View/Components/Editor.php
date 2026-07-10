<?php

namespace Content\App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Editor extends Component
{
    /**
     *   content
     *
     * @var string
     */
    public $content;

    /**
     *   name
     *
     * @var string
     */
    public $name;

    /**
     *   uid
     *
     * @var string
     */
    public $uid;

    /**
     *   label
     *
     * @var string
     */
    public $label;

    /**
     *   required
     *
     * @var bool
     */
    public $required;

    /**
     *   jodit
     *
     * @var bool
     */
    public $jodit;

    /**
     *   monaco
     *
     * @var bool
     */
    public $monaco;

    /**
     *  preview
     *
     * @var bool
     */
    public $preview;


    public $lang;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $content,
        string $name,
        string $label,
        bool $required = false,
        bool $monaco = true,
        bool $jodit = true,
        bool $preview = true,
        string $lang = "html"
    ) {
        $this->content = $content;
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->jodit = $jodit;
        $this->monaco = $monaco;
        $this->preview = $preview;
        $this->lang = $lang;
        $this->uid = 'editor_' . uniqid();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('Content::components.editor');
    }
}
