<?php

namespace ProcessWire;

class AaprBooksHandler extends WireData implements Module
{

    public function init()
    {
        $this->addHook('/api/books/list/', $this, 'bookList');
        $this->addHook('/api/books/add/', $this, 'bookAdd');
        $this->addHook('/api/books/update/{id}', $this, 'bookUpdate');
        $this->addHook('/api/books/delete/{id}', $this, 'bookDelete');
    }

    public function bookList(HookEvent $event)
    {
        $books = $this->wire('pages')->find("template=basic-page");
        // return $_POST;
        return $this->sendHTML('booksList', ['books' => $books]);
    }

    public function bookAdd()
    {
        $title = $this->wire('input')->post('title', 'text');
        $author = $this->wire('input')->post('author', 'text');
        $year = $this->wire('input')->post('year', 'int');
        $name = $this->wire('input')->post('title', 'pageName');

        $message = "Send data to add book.";

        // check if book already exists


        $book = $title . ' ' . $author . ' ' . $year . ' ' . $name;
        // return $_POST;
        return $message . $this->sendHTML('debug', ['message' => $book]);
    }

    public function bookUpdate(HookEvent $event)
    {
        $id = $event->arguments('id'); // Get the first argument passed to the hook
        $message = "Send ID {$id} to update.";
        return $message . $this->sendHTML('debug', ['message' => $message]);
    }

    public function bookDelete(HookEvent $event)
    {
        $id = $event->arguments(0); // Get the first argument passed to the hook
        $message = "Send ID {$id} to delete.";
        return $message . $this->sendHTML('debug', ['message' => $message]);
    }


    protected function sendHTML(string $partial, array $data)
    {
        $twig = $this->wire('modules')->get('TemplateEngineFactory');
        return $twig->render(
            "partials/$partial",
            $data
        );
    }
}
