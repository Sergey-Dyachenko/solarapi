<template>
   <div>
     <div>
         <comment v-for="comment in comments"
         :key="comment.id"
         :comment="comment">
         </comment>
     </div>
   </div>
</template>
<script>
    import comment from './CommentItem'

    export default {
        methods: {
            fetchComments(){
                    const t = this;
                    axios.get('api/comments').then(
                        
                        ({data}) => {
                            t.comments = data,
                            console.log(data)
                            
                        }
                    );            
                },
        },        
            components:{
               comment 
            },
            
            created(){
                this.fetchComments();        
            },
            data: function (){
            return  {
                   state : 'default',
                   data:{
                       body: ''
                   },
                   comments:[],
            }
        }
    }
</script>