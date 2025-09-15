<template>
    <div>
        <h1>Liste des posts</h1>

        <form @submit.prevent="addPost">
            <input v-model="title" placeholder="Titre" required />
            <textarea
                v-model="content"
                placeholder="Contenu"
                required
            ></textarea>
            <button type="submit">Ajouter</button>
        </form>

        <ul>
            <li v-for="post in posts" :key="post.id">
                <strong>{{ post.title }}</strong
                >: {{ post.content }}
            </li>
        </ul>

        <p v-if="error" style="color: red">{{ error }}</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const posts = ref([]);
const title = ref("");
const content = ref("");
const error = ref("");

// Récupérer les posts
const fetchPosts = async () => {
    try {
        const res = await fetch("/api/posts");
        posts.value = await res.json();
    } catch (e) {
        error.value = "Erreur lors de la récupération des posts";
    }
};

onMounted(fetchPosts);

// Ajouter un post
const addPost = async () => {
      console.log("addPost appelé !"); // debug
    try {
        const res = await fetch("/api/posts", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                title: title.value,
                content: content.value,
            }),
        });

        if (!res.ok) throw new Error("Erreur à la création du post");

        const newPost = await res.json();
        posts.value.push(newPost); // Ajouter le post directement dans la liste
        title.value = "";
        content.value = "";
        error.value = "";
    } catch (e) {
        error.value = e.message;
    }
};
</script>
