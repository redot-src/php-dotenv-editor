<?php

use Redot\DotenvEditor\DotenvEditor;
use Redot\DotenvEditor\Exceptions\FileNotFoundException;

beforeEach(function () {
    if (file_exists(__DIR__ . '/.env')) {
        unlink(__DIR__ . '/.env');
    }

    file_put_contents(__DIR__ . '/.env', 'FOO="bar"' . PHP_EOL . 'BAZ="qux"');
});

afterEach(function () {
    if (file_exists(__DIR__ . '/.env')) {
        unlink(__DIR__ . '/.env');
    }

    if (file_exists(__DIR__ . '/.env.backup')) {
        unlink(__DIR__ . '/.env.backup');
    }
});

it('throws an exception if the file does not exist', function () {
    DotenvEditor::make(__DIR__ . '/.non-existing-env');
})->throws(FileNotFoundException::class);

it('loads the environment variables', function () {
    $editor = DotenvEditor::make(__DIR__ . '/.env');

    expect($editor->get('FOO'))->toBe('bar');
    expect($editor->get('BAZ'))->toBe('qux');
});

it('sets the environment variables', function () {
    $editor = DotenvEditor::make(__DIR__ . '/.env');

    $editor->set('FOO', 'baz');
    $editor->set('BAZ', 'quux');

    expect($editor->get('FOO'))->toBe('baz');
    expect($editor->get('BAZ'))->toBe('quux');
});

it('saves the environment variables', function () {
    $editor = DotenvEditor::make(__DIR__ . '/.env');

    $editor->set('FOO', 'baz');
    $editor->set('BAZ', 'quux');

    $editor->save();

    $editor = DotenvEditor::make(__DIR__ . '/.env');

    expect($editor->get('FOO'))->toBe('baz');
    expect($editor->get('BAZ'))->toBe('quux');
});

it('backs up the environment variables', function () {
    $editor = DotenvEditor::make(__DIR__ . '/.env', true);

    $editor->set('FOO', 'baz');
    $editor->set('BAZ', 'quux');

    $editor->save();

    $editor = DotenvEditor::make(__DIR__ . '/.env');

    expect($editor->get('FOO'))->toBe('baz');
    expect($editor->get('BAZ'))->toBe('quux');

    $editor = DotenvEditor::make(__DIR__ . '/.env.backup');

    expect($editor->get('FOO'))->toBe('bar');
    expect($editor->get('BAZ'))->toBe('qux');
});
