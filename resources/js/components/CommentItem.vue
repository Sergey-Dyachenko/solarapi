<template>
<div>
    <div>
        <p>{{comment.text}}</p>
    </div>
    <div>
        {{comment.name}}
    </div>
</div>        
</template>
<script>
export default {
    
    props: {
        comment: {
            required: true,
            type: Object, 
        }
    },

    data : function (){
        return {
                
        }
    },
}
</script>
