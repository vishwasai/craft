pipeline {
   agent any 
   stages
   {
      stage('Checkout')
      {
         steps
         {  
            git credentialsId: 'git', url: 'git@github.com:vishwasai/craft.git'
         }
      }
   }
}
