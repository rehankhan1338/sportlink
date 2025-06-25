<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
  ],
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
  editorProps: {
    attributes: {
      class: 'prose prose-sm focus:outline-none min-h-[200px] p-4'
    }
  }
})

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
  const isSame = newValue === editor.value?.getHTML()
  if (!isSame) {
    editor.value?.commands.setContent(newValue, false)
  }
}, { immediate: true })
</script>

<template>
  <div class="rich-text-editor">
    <div class="editor-menu">
      <div class="menu-group">
        <button @click="editor?.chain().focus().toggleHeading({ level: 1 }).run()" :class="{ 'is-active': editor?.isActive('heading', { level: 1 }) }">
          H1
        </button>
        <button @click="editor?.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'is-active': editor?.isActive('heading', { level: 2 }) }">
          H2
        </button>
        <button @click="editor?.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'is-active': editor?.isActive('heading', { level: 3 }) }">
          H3
        </button>
        <button @click="editor?.chain().focus().toggleBold().run()" :class="{ 'is-active': editor?.isActive('bold') }">
          Bold
        </button>
        <button @click="editor?.chain().focus().toggleItalic().run()" :class="{ 'is-active': editor?.isActive('italic') }">
          Italic
        </button>
        <button @click="editor?.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor?.isActive('bulletList') }">
          Bullet List
        </button>
      </div>
      <div class="menu-group mt-3">
        <button @click="editor?.chain().focus().toggleOrderedList().run()" :class="{ 'is-active': editor?.isActive('orderedList') }">
          Numbered List
        </button>
      </div>
    </div>
    <div class="editor-content" @click="editor?.chain().focus().run()">
      <EditorContent :editor="editor" />
    </div>
  </div>
</template>

<style scoped>
.rich-text-editor {
  border: 1px solid #ddd;
  border-radius: 4px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.editor-menu {
  padding: 8px;
  border-bottom: 1px solid #ddd;
  background-color: #f5f5f5;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.menu-group {
  display: flex;
  gap: 8px;
  padding: 0 8px;
}

.editor-menu button {
  padding: 4px 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: white;
  cursor: pointer;
  font-size: 14px;
  min-width: 40px;
}

.editor-menu button:hover {
  background-color: #f0f0f0;
}

.editor-menu button.is-active {
  background-color: #e0e0e0;
  border-color: #999;
}

.editor-content {
  flex: 1;
  min-height: 200px;
  background: white;
  cursor: text;
}

.editor-content :deep(.ProseMirror) {
  min-height: 200px;
  padding: 16px;
  outline: none;
}

.editor-content :deep(.ProseMirror p) {
  margin: 0;
}

.editor-content :deep(.ProseMirror ul),
.editor-content :deep(.ProseMirror ol) {
  padding-left: 20px;
}

.editor-content :deep(.ProseMirror h1) {
  font-size: 2em;
  margin: 0.67em 0;
}

.editor-content :deep(.ProseMirror h2) {
  font-size: 1.5em;
  margin: 0.83em 0;
}

.editor-content :deep(.ProseMirror h3) {
  font-size: 1.17em;
  margin: 1em 0;
}
</style> 