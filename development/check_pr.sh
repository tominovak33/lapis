if [ -f "pr_test.txt" ]; then
  # IF the pr file exists then call the deployment script
  ./../pr/deploy_pr.sh $(cat pr_test.txt)
  # rm pr_test.txt # Remove the file to indicate that the deploy has been triggered
fi

