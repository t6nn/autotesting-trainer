wget http://localhost:8085/jnlpJars/jenkins-cli.jar -OutFile jenkins-cli.jar
java -jar jenkins-cli.jar -s http://localhost:8085 install-plugin checkstyle cloverphp crap4j dry htmlpublisher jdepend plot pmd violations warnings xunit
java -jar jenkins-cli.jar -s http://localhost:8085 safe-restart
rm jenkins-cli.jar