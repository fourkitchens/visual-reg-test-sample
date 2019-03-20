#!/bin/bash

set -eo pipefail

# Set up environment variables

# By default, we will make the environment name after the circle build number.
DEFAULT_ENV=ci-$CIRCLE_BUILD_NUM

# If there is a PR number provided, though, then we will use it instead.
if [[ -n ${CIRCLE_PULL_REQUEST} ]] ; then
  PR_NUMBER=${CIRCLE_PULL_REQUEST##*/}
  DEFAULT_ENV="pr-${PR_NUMBER}"
fi

#=====================================================================================================================
# EXPORT needed environment variables
#
# Circle CI 2.0 does not yet expand environment variables so they have to be manually EXPORTed
# Once environment variables can be expanded this section can be removed
# See: https://discuss.circleci.com/t/unclear-how-to-work-with-user-variables-circleci-provided-env-variables/12810/11
# See: https://discuss.circleci.com/t/environment-variable-expansion-in-working-directory/11322
# See: https://discuss.circleci.com/t/circle-2-0-global-environment-variables/8681
#=====================================================================================================================
(
  echo "export DEFAULT_ENV='$DEFAULT_ENV'"
  echo 'export TERMINUS_ENV=${TERMINUS_ENV:-$DEFAULT_ENV}'
) >> $BASH_ENV
source $BASH_ENV
