FROM openjdk:8-jre-alpine

RUN \
  apk add --no-cache graphviz wget ca-certificates ttf-dejavu
#apk del wget ca-certificates

ENV PLANTUML_VERSION=1.2019.5
RUN wget "http://downloads.sourceforge.net/project/plantuml/${PLANTUML_VERSION}/plantuml.${PLANTUML_VERSION}.jar" -O plantuml.jar

ENV LANG en_US.UTF-8
ADD config.cfg .

ENTRYPOINT ["java", "-Djava.awt.headless=true", "-jar", "plantuml.jar", "-p"]

CMD ["-tsvg"]
